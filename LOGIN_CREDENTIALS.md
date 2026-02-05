# HRMS Login Credentials

This file contains all test user credentials for the HRMS application.

## Default Password
**All users have the same default password:** `password123`

---

## Employee Login (ESS)

Use any employee email below with password **`password123`**. You will be redirected to the **Employee Self Service (ESS)** portal.

| Email | Password |
|-------|----------|
| `rajesh.kumar@hrms.com` | `password123` |
| `priya.sharma@hrms.com` | `password123` |
| `amit.patel@hrms.com` | `password123` |
| *(see full list below)* | `password123` |

---

## Super Admin

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `admin@hrms.com` | `password123` | Super Admin | Full access to all modules and features |

**Features:**
- Access to Admin Dashboard
- Full CRUD operations on all modules
- Employee Management
- **Employee Tasks** – create and assign tasks for employees (onboarding, training, etc.); URL: `/employee-tasks`
- Attendance Management
- Leave Management & Approval
- Payroll Management
- All reports and analytics

---

## HR Admin

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `hradmin@hrms.com` | `password123` | HR Admin | Admin dashboard and HR modules (no Payroll) |

**Features:**
- Access to Admin Dashboard (same layout as Super Admin)
- **Employees** – view, create, update (no delete)
- **Employee Tasks** – create and assign tasks; URL: `/employee-tasks`
- **Attendance** – view attendance
- **Leaves** – view, create, update, approve leave requests
- **Expenses** – view and approve expenses; manage expense categories
- **Training** – view and manage training
- **Shifts & Roster** – view and manage shifts
- **Assets** – view, create, edit, assign/unassign; manage asset types; approve/decline asset returns
- **Travel** – view and approve travel requests
- **Exit** – view and manage exit requests
- **Performance** – view and manage performance
- **No Payroll** – Payroll menu and dashboard box are hidden for HR Admin

**Quick login:** `hradmin@hrms.com` / `password123` → Admin Dashboard

---

## Who Creates ESS Tasks?

**Employee tasks** (e.g. "Complete onboarding documents", "Attend training session") are created by **HR/Admin** users:

| Role        | Can manage tasks? | Seeded user              |
|------------|--------------------|---------------------------|
| Super Admin| Yes                | `admin@hrms.com`         |
| HR Admin   | Yes                | `hradmin@hrms.com`       |

- **Login as admin:** `admin@hrms.com` / `password123`
- **Manage tasks:** After login, go to **Employee Tasks** (`/employee-tasks`) to create, edit, or assign tasks to employees. Tasks appear on the employee’s ESS **Tasks** page (`/ess/tasks`).

---

## Employee Users (ESS Portal)

All employees are redirected to the Employee Self Service (ESS) portal after login.

| Employee ID | Name | Email | Password | Role |
|-------------|------|-------|----------|------|
| EMP001 | Rajesh Kumar | `rajesh.kumar@hrms.com` | `password123` | Employee |
| EMP002 | Priya Sharma | `priya.sharma@hrms.com` | `password123` | Employee |
| EMP003 | Amit Patel | `amit.patel@hrms.com` | `password123` | Employee |
| EMP004 | Sneha Singh | `sneha.singh@hrms.com` | `password123` | Employee |
| EMP005 | Vikram Reddy | `vikram.reddy@hrms.com` | `password123` | Employee |
| EMP006 | Anjali Mehta | `anjali.mehta@hrms.com` | `password123` | Employee |
| EMP007 | Rahul Verma | `rahul.verma@hrms.com` | `password123` | Employee |
| EMP008 | Kavita Joshi | `kavita.joshi@hrms.com` | `password123` | Employee |
| EMP009 | Suresh Iyer | `suresh.iyer@hrms.com` | `password123` | Employee |
| EMP010 | Meera Nair | `meera.nair@hrms.com` | `password123` | Employee |

**Employee Portal Features:**
- Personal Dashboard
- View & Edit Profile
- View Tasks
- Check-in/Check-out Attendance
- Apply for Leaves
- View Leave History
- View Payslips
- Download Payslip PDFs

---

## Quick Login Guide

### For Testing Admin Features:
1. Go to: `http://localhost/login` (or your app URL)
2. **Super Admin:** Email: `admin@hrms.com` / Password: `password123`
3. **HR Admin:** Email: `hradmin@hrms.com` / Password: `password123`
4. You'll be redirected to the Admin Dashboard (Payroll menu hidden for HR Admin)

### For Testing Employee Features:
1. Go to: `http://localhost/login` (or your app URL)
2. Use any employee email (e.g., `rajesh.kumar@hrms.com`)
3. Password: `password123`
4. You'll be redirected to the ESS Dashboard

---

## Sample Data

Each employee has:
- ✅ 30 days of attendance records
- ✅ Leave requests (pending, approved, rejected)
- ✅ 3 months of payroll records
- ✅ Complete profile information
- ✅ Bank details
- ✅ KYC documents

---

## Security Note

⚠️ **IMPORTANT:** These are test credentials. Change all passwords in production!

To change a user's password, you can:
1. Use Laravel Tinker: `php artisan tinker`
2. Run: `User::where('email', 'admin@hrms.com')->update(['password' => bcrypt('newpassword')])`

---

## Role-Based Access

### Super Admin
- Full system access
- Can manage all employees
- Can approve/reject leaves
- Can run payroll
- Can view all reports

### Employee
- Limited to ESS portal
- Can only view/edit own data
- Can apply for leaves
- Can check-in/check-out
- Cannot access admin modules

---

## Troubleshooting

### If login fails:
1. Make sure database is migrated: `php artisan migrate`
2. Make sure seeders are run: `php artisan db:seed`
3. Check `.env` file database configuration
4. Clear cache: `php artisan cache:clear && php artisan config:clear`

### If HR Admin user does not exist:
1. Run: `php artisan db:seed --class=DatabaseSeeder` (uses `firstOrCreate`, safe to re-run)
2. HR Admin login: `hradmin@hrms.com` / `password123`

### If employee can't see ESS portal:
1. Verify user has 'Employee' role: `php artisan tinker` → `User::find(1)->roles`
2. Verify employee record exists: `User::find(1)->employee`
3. Check routes: `php artisan route:list | grep ess`

---

## Additional Roles (Available but not seeded)

The following roles exist in the system; **HR Admin** has a seeded user (`hradmin@hrms.com`). Others do not:
- **HR Admin** – Seeded: `hradmin@hrms.com` (see HR Admin section above)
- **Manager** – Can view team attendance and approve leaves
- **Finance** – Can manage payroll
- **Recruiter** – Can manage recruitment

To create users with these roles, use:
```php
$user = User::create([...]);
$user->assignRole('Manager'); // or 'Finance', 'Recruiter'
```


