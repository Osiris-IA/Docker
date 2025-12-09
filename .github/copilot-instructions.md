# Copilot Instructions - Projet Docker Laravel

## Architecture Overview

This is a **dual Laravel application** setup running in Docker containers with shared database and services. The architecture uses:

- **Two identical Laravel 11 applications** (`src1/` and `src2/`)
- **Shared MySQL database** (`tp_mysql`) - migrations run ONLY from `php1` container (controlled by `IS_PRIMARY=true`)
- **Separate Nginx/PHP-FPM pairs** - `nginx1:8081` → `php1`, `nginx2:8082` → `php2`
- **MinIO S3-compatible storage** at `:9003` (API) and `:9004` (console)
- **Mailpit email testing** at `:8025` (web UI) and `:1025` (SMTP)

### Critical: Primary/Secondary Pattern
- `php1` container has `IS_PRIMARY=true` - runs migrations and seeds
- `php2` container has `IS_PRIMARY=false` - skips migrations
- Both apps share the same database schema but can have independent code

## Development Workflow

### Starting the Environment
```bash
docker-compose up -d
# Access apps at:
# - App 1: http://localhost:8081
# - App 2: http://localhost:8082
# - MinIO Console: http://localhost:9004
# - Mailpit: http://localhost:8025
```

### Running Commands Inside Containers
```bash
# Artisan commands
docker exec -it tp_php1 php artisan migrate
docker exec -it tp_php1 php artisan make:controller ExampleController

# Composer (from inside container or build time)
docker exec -it tp_php1 composer require package/name

# NPM build (for Vite assets)
docker exec -it tp_php1 npm run dev
```

### Container Restart Behavior
The `entrypoint.sh` script automatically:
1. Creates `.env` from `.env.example` if missing
2. Installs Composer dependencies if `vendor/autoload.php` is missing
3. Installs NPM dependencies and builds assets if `node_modules/` is missing
4. Generates `APP_KEY` if empty
5. Waits for MySQL to be ready
6. Runs `migrate:fresh --seed` ONLY if `IS_PRIMARY=true`

### Database Connection
Both Laravel apps connect to the same MySQL instance using environment variables injected via `docker-compose.yml`:
- `DB_HOST=db` (service name resolves internally)
- `DB_DATABASE=laravel_db`
- `DB_USERNAME=user`
- `DB_PASSWORD=password`

**Never hardcode credentials** - they're set in docker-compose and passed to containers.

## MinIO (S3) Storage Configuration

MinIO is pre-configured in both Laravel apps via environment variables:
```env
AWS_ENDPOINT=http://tp_minio:9000  # Internal container communication
AWS_URL=http://127.0.0.1:9003      # External browser access
AWS_USE_PATH_STYLE_ENDPOINT=true   # Required for MinIO
```

To use S3 storage in Laravel:
```php
Storage::disk('s3')->put('file.txt', 'contents');
```

Access stored files at: `http://localhost:9003/laravel-bucket/file.txt`

## Nginx Configuration Pattern

Each Nginx container has a separate config file:
- `nginx1` → `docker/nginx/default.conf` → proxies to `tp_php1:9000`
- `nginx2` → `docker/nginx/default2.conf` → proxies to `tp_php2:9000`

**Key difference**: `fastcgi_pass` points to different PHP-FPM containers. When modifying Nginx configs, ensure the `fastcgi_pass` matches the correct PHP service name.

## Testing Email (Mailpit)

All emails are captured by Mailpit instead of being sent externally. Configure in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=tp_mailpit
MAIL_PORT=1025
```

View emails at: http://localhost:8025

## Common Pitfalls

### Corrupted vendor/ Directory
If Composer autoload fails, `entrypoint.sh` auto-detects and reinstalls:
```bash
# Manually trigger if needed:
docker exec -it tp_php1 rm -rf vendor
docker restart tp_php1
```

### Migration Conflicts
- Always run migrations from `php1` (primary) container
- If both containers try to migrate, you'll get race conditions
- Check `IS_PRIMARY` environment variable before running schema changes

### Permission Issues
PHP-FPM runs as `www-data`. If you create files manually in `storage/` or `bootstrap/cache/`, fix permissions:
```bash
docker exec -it tp_php1 chown -R www-data:www-data storage bootstrap/cache
docker exec -it tp_php1 chmod -R 775 storage bootstrap/cache
```

## File Locations

- Laravel apps: `src1/` and `src2/`
- Docker configs: `docker/php/Dockerfile`, `docker/php/entrypoint.sh`
- Nginx configs: `docker/nginx/default.conf`, `docker/nginx/default2.conf`
- Orchestration: `docker-compose.yml` at root

## Environment Variables

Never commit `.env` files. The containers auto-generate them from `.env.example` on first run. To update environment:
1. Modify `docker-compose.yml` environment section
2. Restart affected containers: `docker-compose restart php1 php2`
