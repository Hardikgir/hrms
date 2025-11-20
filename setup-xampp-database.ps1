Write-Host "========================================" -ForegroundColor Cyan
Write-Host "HRMS XAMPP Database Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Step 1: Checking XAMPP MySQL..." -ForegroundColor Yellow
Write-Host "Please make sure XAMPP MySQL service is running" -ForegroundColor Yellow
Write-Host ""
Read-Host "Press Enter to continue"

Write-Host ""
Write-Host "Step 2: Database Creation" -ForegroundColor Yellow
Write-Host "Please open phpMyAdmin (http://localhost/phpmyadmin)" -ForegroundColor Yellow
Write-Host "and create a database named 'hrms'" -ForegroundColor Yellow
Write-Host ""
Read-Host "Press Enter after creating the database"

Write-Host ""
Write-Host "Step 3: Updating .env file..." -ForegroundColor Yellow
$envContent = @"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrms
DB_USERNAME=root
DB_PASSWORD=
"@

Write-Host "Please update your .env file with the following:" -ForegroundColor Yellow
Write-Host $envContent -ForegroundColor Green
Write-Host ""
Read-Host "Press Enter after updating .env file"

Write-Host ""
Write-Host "Step 4: Clearing config cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
Write-Host ""

Write-Host "Step 5: Running migrations..." -ForegroundColor Yellow
php artisan migrate
Write-Host ""

Write-Host "Step 6: Seeding database..." -ForegroundColor Yellow
php artisan db:seed
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Default Login:" -ForegroundColor Cyan
Write-Host "Email: admin@hrms.com" -ForegroundColor White
Write-Host "Password: password123" -ForegroundColor White
Write-Host ""

