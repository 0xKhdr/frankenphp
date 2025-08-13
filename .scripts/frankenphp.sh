#!/bin/bash
source ./.scripts/common.sh

DOCKER_COMPOSE_FILE="docker-compose-frankenphp.yml"

# Function to check if php service exists in Docker Compose file
check_php_service() {
    if ! docker compose -f "$DOCKER_COMPOSE_FILE" config --services | grep -q "^php$"; then
        print_error "Service 'php' not found in $DOCKER_COMPOSE_FILE"
        exit 1
    fi
}

# Function to check and create .env file
check_env_file() {
    if [ ! -f .env ]; then
        print_warning ".env file not found. Creating from .env.example..."
        cp .env.example .env
    fi
}

# Function to build and start containers
start_containers() {
    print_status "Building and starting FrankenPHP containers..."
    docker compose -f "$DOCKER_COMPOSE_FILE" up -d --build --remove-orphans
}

# Function to wait for services
wait_for_services() {
    print_status "Waiting for services to be healthy..."
    sleep 10
}

# Function to run Laravel setup commands
run_laravel_setup() {
    print_status "Running Laravel setup..."
    local commands=(
        "php artisan key:generate --force"
        "php artisan migrate --force"
        "php artisan config:cache"
        "php artisan route:cache"
        "php artisan view:cache"
    )

    for cmd in "${commands[@]}"; do
        docker compose -f "$DOCKER_COMPOSE_FILE" exec -T php $cmd
    done
}

# Function to run stress test
run_stress_test() {
    local url="http://php.localhost"
    local concurrency=1
    local timeout=30

    # Parse arguments
    while [ $# -gt 0 ]; do
        case "$1" in
            -c|--concurrency)
                shift
                concurrency="$1"
                ;;
            -t|--timeout)
                shift
                timeout="$1"
                ;;
            http*://*)
                url="$1"
                ;;
            *)
                print_error "Invalid argument: $1. Usage: $0 [url] [-c concurrency] [-t timeout]"
                exit 1
                ;;
        esac
        shift
    done

    # Validate numerical inputs
    if ! [[ "$concurrency" =~ ^[0-9]+$ ]] || [ "$concurrency" -le 0 ]; then
        print_error "Concurrency must be a positive integer"
        exit 1
    fi
    if ! [[ "$timeout" =~ ^[0-9]+$ ]] || [ "$timeout" -le 0 ]; then
        print_error "Timeout must be a positive integer"
        exit 1
    fi

    print_status "Running stress test on $url with $concurrency concurrent users, $timeout seconds timeout..."
    docker compose -f "$DOCKER_COMPOSE_FILE" exec -T php ./vendor/bin/pest stress "$url" --concurrency="$concurrency" --duration="$timeout"
}

# Function to stop containers
stop_containers() {
    print_status "Stopping containers..."
    docker compose -f "$DOCKER_COMPOSE_FILE" down
}

# Function to remove volumes
remove_volumes() {
    read -p "Remove volumes? (y/N): " -r
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_warning "Removing volumes..."
        docker compose -f "$DOCKER_COMPOSE_FILE" down -v
        docker volume prune -f
    fi
}

# Function to execute arbitrary commands in the container
exec_command() {
    print_status "Executing command in php container: $*"
    docker compose -f "$DOCKER_COMPOSE_FILE" exec php "$@"
}

# Function to handle 'up' command
up_command() {
    print_header "Starting FrankenPHP Stack"
    check_env_file
    check_php_service
    start_containers
    wait_for_services
    run_laravel_setup

    print_status "FrankenPHP stack is ready!"
    print_status "Application: http://localhost"
    print_status "HTTPS: https://localhost"
    print_status "HTTP/3: Available on port 443/udp"
}

# Function to handle 'down' command
down_command() {
    print_header "Stopping FrankenPHP Stack"
    check_php_service
    stop_containers
    remove_volumes
    print_status "FrankenPHP stack stopped!"
}

# Function to handle 'stress' command
stress_command() {
    print_header "Stress Testing FrankenPHP Stack"
    check_php_service
    shift
    run_stress_test "$@"
    print_status "Stress test completed!"
}

# Function to handle 'exec' command
exec_command_handler() {
    print_header "Executing Command in FrankenPHP Stack"
    check_php_service
    shift
    if [ $# -eq 0 ]; then
        print_error "No command provided for exec. Usage: $0 exec <command>"
        exit 1
    fi
    exec_command "$@"
}

# Main execution
main() {
    if [ $# -eq 0 ]; then
        print_error "No command provided. Usage: $0 {up|down|stress [url] [-n requests] [-c concurrency] [-t timeout]|exec <command>}"
        exit 1
    fi

    case "$1" in
        up)
            up_command
            ;;
        down)
            down_command
            ;;
        stress)
            stress_command "$@"
            ;;
        exec)
            exec_command_handler "$@"
            ;;
        *)
            print_error "Invalid command. Usage: $0 {up|down|stress [url] [-c concurrency] [-t timeout]|exec <command>}"
            exit 1
            ;;
    esac
}

# Execute main function with arguments
main "$@"
