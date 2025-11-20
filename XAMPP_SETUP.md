# XAMPP Database Setup Guide

## Step 1: Start XAMPP Services

1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service

## Step 2: Create Database in phpMyAdmin

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on **"New"** in the left sidebar to create a new database
3. Enter database name: `hrms`
4. Select **Collation**: `utf8mb4_unicode_ci`
5. Click **"Create"**

## Step 3: Configure Laravel .env File

Update your `.env` file with the following database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrms
DB_USERNAME=root
DB_PASSWORD=
```

**Note:** By default, XAMPP MySQL has:
- Username: `root`
- Password: (empty/blank)

If you've set a password for MySQL, use that instead.

## Step 4: Run Migrations

Open terminal/command prompt in your project directory and run:

```bash
php artisan migrate
```

This will create all the necessary tables in your database.

## Step 5: Seed the Database

Run the seeder to create default roles, permissions, and admin user:

```bash
php artisan db:seed
```

## Step 6: Verify in phpMyAdmin

1. Go back to phpMyAdmin: `http://localhost/phpmyadmin`
2. Select the `hrms` database from the left sidebar
3. You should see all the tables created:
   - users
   - employees
   - departments
   - designations
   - locations
   - attendances
   - leaves
   - payrolls
   - And many more...

## Default Login Credentials

After seeding, you can login with:
- **Email**: admin@hrms.com
- **Password**: password123

## Troubleshooting

### Error: Access denied for user 'root'@'localhost'

**Solution:**
1. Check if MySQL password is set in XAMPP
2. Update `DB_PASSWORD` in `.env` file
3. Or reset MySQL password in XAMPP

### Error: SQLSTATE[HY000] [2002] No connection could be made

**Solution:**
1. Make sure MySQL service is running in XAMPP
2. Check if port 3306 is correct
3. Try `DB_HOST=localhost` instead of `127.0.0.1`

### Error: SQLSTATE[42000] Unknown database 'hrms'

**Solution:**
1. Make sure you created the database in phpMyAdmin
2. Check database name in `.env` matches exactly

### Clear Cache After .env Changes

If you changed `.env` file, clear config cache:

```bash
php artisan config:clear
php artisan cache:clear
```

