# HRMS Architecture Documentation

## Overview

The HRMS (Human Resource Management System) is built using Laravel 12 with a modular architecture. The application follows MVC pattern and uses AdminLTE v3 for the frontend interface.

## Architecture Pattern

### Modular Architecture

The application is structured in modules, each module containing:
- **Models**: Database entities
- **Controllers**: Web and API controllers
- **Resources**: API resource transformers
- **Requests**: Form request validation
- **Services**: Business logic
- **Repositories**: Data access layer (optional)
- **Routes**: Module-specific routes
- **Views**: Blade templates
- **Migrations**: Database schema
- **Seeders**: Data seeders
- **Factories**: Model factories for testing

### Module Structure

```
app/Modules/{ModuleName}/
├── Models/
├── Controllers/
│   ├── {Module}Controller.php (Web)
│   └── {Module}ApiController.php (API)
├── Resources/
├── Requests/
├── Services/
├── Routes/
├── Views/
└── Migrations/
```

## Technology Stack

### Backend
- **Framework**: Laravel 12
- **PHP Version**: 8.2+
- **Database**: MySQL 8.0 / PostgreSQL
- **Cache/Queue**: Redis
- **Queue Worker**: Laravel Horizon

### Frontend
- **UI Framework**: AdminLTE v3
- **CSS Framework**: Bootstrap 4
- **JavaScript**: jQuery
- **Data Tables**: DataTables
- **Select Dropdowns**: Select2
- **Alerts**: SweetAlert2
- **Date Picker**: daterangepicker

### DevOps
- **Containerization**: Docker & Docker Compose
- **CI/CD**: GitHub Actions
- **Web Server**: Nginx
- **PHP-FPM**: PHP 8.2 FPM

## Database Design

### Key Principles
- UUID for all entities
- Soft deletes for data retention
- Created/Updated by tracking
- Indexing on frequently queried fields
- Foreign key constraints
- JSON fields for flexible data (salary structures, documents metadata)

### Core Tables
- `users`: Authentication and user accounts
- `employees`: Employee master data
- `departments`: Department hierarchy
- `designations`: Job designations
- `locations`: Office locations
- `attendances`: Daily attendance records
- `leaves`: Leave requests
- `payrolls`: Monthly payroll records
- `salary_structures`: Employee salary breakdowns

## Security

### Authentication
- Laravel Sanctum for API authentication
- Session-based authentication for web

### Authorization
- Spatie Laravel Permission for RBAC
- Role-based access control
- Permission-based feature access

### Data Protection
- CSRF protection on all forms
- Encrypted sensitive fields (bank details)
- Rate limiting on login and sensitive APIs
- Two-factor authentication (optional for admins)

## API Design

### RESTful API
- Standard HTTP methods (GET, POST, PUT, DELETE)
- Resource-based URLs
- JSON responses
- API versioning (future)

### API Documentation
- Swagger/OpenAPI 3.0
- Auto-generated from annotations
- Available at `/api/documentation`

## Queue & Jobs

### Queue System
- Redis as queue driver
- Laravel Horizon for queue management
- Background job processing

### Scheduled Tasks
- Daily attendance summary
- Monthly payroll processing
- Document expiry reminders
- Compliance report generation

## Testing Strategy

### Unit Tests
- Model tests
- Service class tests
- Calculation engine tests

### Feature Tests
- API endpoint tests
- Web route tests
- Workflow tests (leave approval, payroll run)

### Integration Tests
- Database operations
- External service integrations

## Deployment

### Docker Setup
- Multi-container architecture
- Separate containers for app, nginx, db, redis
- Horizon worker container
- Scheduler container

### Environment Configuration
- Environment-based configuration
- Secure credential management
- Database migrations on deployment

## Performance Optimization

### Caching Strategy
- Redis for session and cache
- Query result caching
- View caching for static content

### Database Optimization
- Indexing on foreign keys and frequently queried fields
- Query optimization
- Eager loading relationships

### Asset Optimization
- Vite for asset bundling
- Minification of CSS/JS
- CDN for static assets (optional)

## Monitoring & Logging

### Application Monitoring
- Laravel Telescope for development
- Error tracking
- Performance monitoring

### Logging
- File-based logging
- Log rotation
- Error notification (optional)

## Backup Strategy

### Database Backups
- Scheduled database backups
- S3 storage for backups (spatie/laravel-backup)
- Retention policy

### File Backups
- Document storage backup
- Profile picture backup

## Future Enhancements

- Microservices architecture (optional)
- Real-time notifications (WebSockets)
- Mobile app API
- Advanced analytics and reporting
- AI-powered insights

