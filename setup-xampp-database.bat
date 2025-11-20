@echo off
echo ========================================
echo HRMS XAMPP Database Setup
echo ========================================
echo.

echo Step 1: Please make sure XAMPP MySQL is running
echo.
pause

echo Step 2: Creating database in MySQL...
echo.
echo Please open phpMyAdmin (http://localhost/phpmyadmin)
echo and create a database named 'hrms'
echo.
pause

echo Step 3: Updating .env file...
echo.
echo Please update your .env file with:
echo DB_CONNECTION=mysql
echo DB_HOST=127.0.0.1
echo DB_PORT=3306
echo DB_DATABASE=hrms
echo DB_USERNAME=root
echo DB_PASSWORD=
echo.
pause

echo Step 4: Clearing config cache...
php artisan config:clear
php artisan cache:clear
echo.

echo Step 5: Running migrations...
php artisan migrate
echo.

echo Step 6: Seeding database...
php artisan db:seed
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Default Login:
echo Email: admin@hrms.com
echo Password: password123
echo.
pause

