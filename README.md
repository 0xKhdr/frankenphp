# Laravel with FrankenPHP on Docker

This is a Laravel application running on FrankenPHP with Docker.

## Prerequisites

- Docker and Docker Compose installed on your system
- Git (optional, for version control)

## Getting Started

1. **Clone the repository**
   ```bash
   git clone <repository-url> .
   ```

2. **Copy the environment file**
   ```bash
   cp .env.example .env
   ```

3. **Start the containers**
   ```bash
   docker-compose up -d
   ```

4. **Install PHP dependencies**
   ```bash
   docker-compose exec app composer install
   ```

5. **Generate application key**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Run database migrations**
   ```bash
   docker-compose exec app php artisan migrate
   ```

7. **Access the application**
   - Web: http://localhost:8000
   - phpMyAdmin: http://localhost:8080

## Development

- The `src` directory is mounted as a volume, so you can edit files directly on your host machine
- The application will automatically reload when you make changes to PHP files

## Available Services

- **app**: Laravel application running on FrankenPHP
- **db**: MySQL 8.0 database
- **phpmyadmin**: Web-based database management tool

## Environment Variables

Edit the `.env` file to configure your application environment variables.

## Stopping the Application

To stop the application, run:

```bash
docker-compose down
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
