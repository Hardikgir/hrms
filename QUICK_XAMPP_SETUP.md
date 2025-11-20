# Quick XAMPP Database Setup

## Quick Steps

### 1. Start XAMPP Services
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

### 2. Create Database in phpMyAdmin
1. Go to: `http://localhost/phpmyadmin`
2. Click **"New"** in left sidebar
3. Database name: `hrms`
4. Collation: `utf8mb4_unicode_ci`
5. Click **"Create"**

### 3. Update .env File

Open `.env` file in your project root and update these lines:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrms
DB_USERNAME=root
DB_PASSWORD=
```

**Note:** Leave `DB_PASSWORD` empty if you haven't set a MySQL password in XAMPP.

### 4. Run These Commands

Open terminal/command prompt in your project directory:

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Run migrations (creates all tables)
php artisan migrate

# Seed database (creates admin user, roles, permissions)
php artisan db:seed
```

### 5. Verify Setup

1. Go back to phpMyAdmin: `http://localhost/phpmyadmin`
2. Select `hrms` database
3. You should see tables like:
   - users
   - employees
   - departments
   - designations
   - locations
   - attendances
   - leaves
   - payrolls
   - And more...

### 6. Login to Application

- URL: `http://localhost:8000/login`
- Email: `admin@hrms.com`
- Password: `password123`

## Troubleshooting

### MySQL Won't Start
- Check if port 3306 is already in use
- Try changing MySQL port in XAMPP settings

### Access Denied Error
- Check if MySQL has a password set
- Update `DB_PASSWORD` in `.env` if needed

### Database Not Found
- Make sure you created the database in phpMyAdmin
- Check database name matches exactly in `.env`

### Can't Connect
- Make sure MySQL service is running in XAMPP
- Try `DB_HOST=localhost` instead of `127.0.0.1`

## Alternative: Use SQLite (No Setup Needed)

If you want to use SQLite instead (no MySQL setup needed), keep these in `.env`:

```env
DB_CONNECTION=sqlite
```

And make sure `database/database.sqlite` file exists (it should already be there).

