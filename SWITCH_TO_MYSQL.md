# ✅ Successfully Switched to MySQL!

Your `.env` file has been updated to use MySQL instead of SQLite.

## Current Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrms
DB_USERNAME=root
DB_PASSWORD=
```

## Next Steps

### Step 1: Start XAMPP MySQL
- Open XAMPP Control Panel
- Make sure **MySQL** service is running (green)

### Step 2: Create Database in phpMyAdmin

**Option A: Using phpMyAdmin Interface**
1. Go to: `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar
3. Database name: `hrms`
4. Collation: `utf8mb4_unicode_ci`
5. Click **"Create"**

**Option B: Using SQL (Faster)**
1. Go to: `http://localhost/phpmyadmin`
2. Click on **"SQL"** tab
3. Paste this SQL:
```sql
CREATE DATABASE IF NOT EXISTS `hrms` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```
4. Click **"Go"**

### Step 3: Run Migrations

After creating the database, run:

```bash
php artisan migrate
```

This will create all the necessary tables.

### Step 4: Seed Database (Optional but Recommended)

```bash
php artisan db:seed
```

This creates:
- Default admin user (admin@hrms.com / password123)
- Roles and permissions
- Sample departments, designations, locations

### Step 5: Verify

1. Go back to phpMyAdmin
2. Select `hrms` database
3. You should see all tables created

## Test Connection

You can test if everything is working:

```bash
php artisan tinker
```

Then in tinker:
```php
DB::connection()->getPdo();
// Should return: PDO object without errors
```

## Troubleshooting

### Error: Unknown database 'hrms'
- **Solution**: Make sure you created the database in phpMyAdmin first

### Error: Access denied for user 'root'@'localhost'
- **Solution**: 
  - Check if MySQL has a password set
  - Update `DB_PASSWORD` in `.env` if needed
  - Or reset MySQL password in XAMPP

### Error: SQLSTATE[HY000] [2002] No connection
- **Solution**:
  - Make sure MySQL service is running in XAMPP
  - Check if port 3306 is correct
  - Try `DB_HOST=localhost` instead of `127.0.0.1`

### Clear Cache After Changes
```bash
php artisan config:clear
php artisan cache:clear
```

## Done! 🎉

Once the database is created and migrations are run, your application will be using MySQL instead of SQLite.

