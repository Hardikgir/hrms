# HRMS - Human Resource Management System

A comprehensive, production-ready Laravel-based HRMS web application using AdminLTE v3 as the UI theme.

## Features

- **Employee Management**: Complete employee lifecycle management
- **Attendance Tracking**: Web check-in/out, GPS tracking, biometrics integration
- **Leave Management**: Leave types, accrual, approval workflows
- **Payroll Processing**: Salary structures, statutory calculations, payslip generation
- **Recruitment**: Job postings, applicant tracking, interview scheduling
- **Onboarding**: Checklist templates, automated task assignment
- **Performance Management**: KRAs/OKRs, review cycles, ratings
- **Expense Management**: Categories, receipts, approval workflows
- **Training & Development**: Courses, learning paths, certifications
- **Asset Management**: Asset register, assignment, tracking
- **Document Management**: Employee documents, expiry reminders
- **Travel Management**: Travel requests, approvals, expense integration
- **Exit Management**: Resignation flow, clearance checklist
- **Compliance**: PF, ESI, PT, TDS reports and filing

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: MySQL/PostgreSQL
- **Frontend**: AdminLTE v3, Bootstrap 4, jQuery, DataTables, Select2, SweetAlert2
- **Queue**: Laravel Horizon (Redis)
- **Cache**: Redis
- **PDF**: DomPDF
- **API Documentation**: Swagger/OpenAPI 3.0
- **Testing**: PHPUnit, Pest (optional)

## Installation

### Using Docker (Recommended)

```bash
# Clone the repository
git clone <repository-url>
cd nhrms

# Copy environment file
cp .env.example .env

# Update .env with your database credentials
# DB_CONNECTION=mysql
# DB_HOST=db
# DB_PORT=3306
# DB_DATABASE=hrms
# DB_USERNAME=root
# DB_PASSWORD=root

# Start Docker containers
docker-compose up -d

# Install dependencies
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed

# Create storage link
docker-compose exec app php artisan storage:link
```

### Manual Installation

```bash
# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start development server
php artisan serve
```

## Default Credentials

- **Email**: admin@hrms.com
- **Password**: password123

## Roles & Permissions

The system uses Spatie Laravel Permission with the following roles:

- **Super Admin**: Full access to all modules
- **HR Admin**: Employee, Attendance, Leave management
- **Manager**: Team management, leave approvals
- **Finance**: Payroll, Expense management
- **Recruiter**: Recruitment module access
- **Employee**: Self-service portal access

## API Documentation

API documentation is available at `/api/documentation` after setting up Swagger.

## Testing

```bash
# Run PHPUnit tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## Docker Services

- **app**: PHP-FPM application
- **nginx**: Web server
- **db**: MySQL database
- **redis**: Cache and queue
- **horizon**: Queue worker
- **scheduler**: Laravel scheduler

## Project Structure

```
app/
├── Modules/
│   ├── Employee/
│   ├── Attendance/
│   ├── Leave/
│   ├── Payroll/
│   ├── Recruitment/
│   ├── Onboarding/
│   ├── Performance/
│   ├── Expense/
│   ├── Training/
│   ├── Shift/
│   ├── Asset/
│   ├── Document/
│   ├── Travel/
│   ├── Exit/
│   ├── Compliance/
│   ├── Settings/
│   └── Auth/
```

## License

MIT License

## Support

For support, email support@hrms.com or create an issue in the repository.
