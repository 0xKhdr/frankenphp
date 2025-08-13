#!/bin/bash
source ./.scripts/common.sh

print_header "Cleaning Up Docker Resources"

print_warning "This will stop all containers and remove unused resources"
read -p "Continue? (y/N): " -r
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    print_status "Cleanup cancelled"
    exit 0
fi

# Stop all stacks
print_status "Stopping all stacks..."
docker-compose -f docker-compose-frankenphp.yml down 2>/dev/null || true
docker-compose -f docker-compose-nginx.yml down 2>/dev/null || true
docker-compose -f docker-compose-caddy.yml down 2>/dev/null || true

# Clean up resources
print_status "Cleaning up unused resources..."
docker system prune -f
docker volume prune -f
docker network prune -f

print_status "Cleanup completed!"
