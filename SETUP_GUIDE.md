# HRMS Setup Guide

## Prerequisites

- Docker & Docker Compose (for containerized setup)
- OR PHP 8.2+, Composer, MySQL/PostgreSQL, Redis (for manual setup)
- Node.js & NPM (for frontend assets)

## Quick Setup with Docker

### Step 1: Clone and Navigate
```bash
cd nhrms
```

### Step 2: Configure Environment
```bash
cp .env.example .env
```

Edit `.env` file:
```env
APP_NAME=HRMS
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=hrms
DB_USERNAME=root
DB_PASSWORD=root

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Step 3: Start Docker Containers
```bash
docker-compose up -d
```

### Step 4: Install Dependencies
```bash
docker-compose exec app composer install
```

### Step 5: Generate Application Key
```bash
docker-compose exec app php artisan key:generate
```

### Step 6: Run Migrations
```bash
docker-compose exec app php artisan migrate
```

### Step 7: Seed Database
```bash
docker-compose exec app php artisan db:seed
```

### Step 8: Create Storage Link
```bash
docker-compose exec app php artisan storage:link
```

### Step 9: Install Frontend Dependencies (Optional)
```bash
npm install
npm run build
```

### Step 10: Access Application
- Web Interface: http://localhost:8080
- API: http://localhost:8080/api
- API Documentation: http://localhost:8080/api/documentation

## Default Login Credentials

- **Email**: admin@hrms.com
- **Password**: password123
- **Role**: Super Admin

## Manual Setup (Without Docker)

### Step 1: Install Dependencies
```bash
composer install
npm install
```

### Step 2: Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrms
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3: Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### Step 4: Build Assets
```bash
npm run build
```

### Step 5: Start Development Server
```bash
php artisan serve
```

Access at: http://localhost:8000

## Post-Installation

### Create Additional Users
```bash
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'name' => 'HR Admin',
    'email' => 'hr@hrms.com',
    'password' => bcrypt('password123'),
]);
$user->assignRole('HR Admin');
```

### Run Queue Worker
```bash
php artisan queue:work
# OR with Horizon
php artisan horizon
```

### Run Scheduler
Add to crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Troubleshooting

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Database Connection Issues
- Check database credentials in `.env`
- Ensure database server is running
- Verify database exists

### Docker Issues
```bash
# Rebuild containers
docker-compose down
docker-compose up -d --build

# View logs
docker-compose logs -f app
```

## Next Steps

1. Configure email settings in `.env` for notifications
2. Set up Redis for caching and queues
3. Configure file storage (local/S3)
4. Set up SSL certificates for production
5. Configure backup schedules
6. Review and customize roles/permissions

## Production Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Generate optimized autoloader: `composer install --optimize-autoloader --no-dev`
3. Cache configuration: `php artisan config:cache`
4. Cache routes: `php artisan route:cache`
5. Cache views: `php artisan view:cache`
6. Set up SSL/HTTPS
7. Configure proper file permissions
8. Set up monitoring and logging
9. Configure backups
10. Set up CI/CD pipeline

