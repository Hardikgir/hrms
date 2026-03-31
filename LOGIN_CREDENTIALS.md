# HRMS Login Credentials

This file contains all test user credentials for the HRMS application.

## Default Password
**All users have the same default password:** `password123`

---

## Quick Reference – All Users

| Role | Email | Password | Portal |
|------|-------|----------|--------|
| Super Admin | `admin@hrms.com` | `password123` | Admin Dashboard |
| HR Admin | `hradmin@hrms.com` | `password123` | Admin Dashboard |
| HR Manager | `hrmanager@hrms.com` | `password123` | Admin Dashboard |
| HR Employee | `hremployee@hrms.com` | `password123` | ESS Portal |
| Manager | `manager@hrms.com` | `password123` | Admin Dashboard |
| Finance | `finance@hrms.com` | `password123` | Admin Dashboard |
| Recruiter | `recruiter@hrms.com` | `password123` | Admin Dashboard |
| Employee | `rajesh.kumar@hrms.com` | `password123` | ESS Portal |

---

## Super Admin

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `admin@hrms.com` | `password123` | Super Admin | Full access to all modules and features |

**Features:**
- Full system access – all CRUD operations on all modules
- Employee Management
- **Employee Tasks** – create and assign tasks; URL: `/employee-tasks`
- Attendance Management
- Leave Management & Approval
- Payroll Management
- Expenses, Training, Shifts, Assets, Travel, Exit
- Performance Management
- Role & Permission management
- All reports and analytics

---

## HR Admin

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `hradmin@hrms.com` | `password123` | HR Admin | Admin dashboard and full HR modules |

**Features:**
- Access to Admin Dashboard (same layout as Super Admin)
- **Employees** – view, create, update, delete
- **Employee Tasks** – create and assign tasks; URL: `/employee-tasks`
- **Attendance** – view, create, update
- **Leaves** – view, create, update, approve
- **Payroll** – view, create, update, run payroll
- **Expenses** – view, approve, process reimbursements; manage expense categories
- **Training** – view and manage
- **Shifts & Roster** – view and manage
- **Assets** – view, create, edit, assign/unassign; manage types; approve returns
- **Travel** – view and approve
- **Exit** – view and manage
- **Performance** – view and manage
- **Settings** – manage employment types, statuses, departments, designations, locations

---

## HR Manager

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `hrmanager@hrms.com` | `password123` | HR Manager | View & approve – no settings, no employee CRUD, no run payroll |

**Features:**
- Access to Admin Dashboard
- **Employees** – view only (no create/update/delete)
- **Employee Tasks** – create and assign tasks
- **Attendance** – view, create, update
- **Leaves** – view and approve
- **Expenses** – view and approve (no reimbursement processing)
- **Training** – view only
- **Shifts** – view and manage
- **Assets** – approve returns only (no CRUD)
- **Travel** – view and approve
- **Exit** – view and manage
- **Performance** – view and manage
- **Payroll** – view only (cannot create or run payroll)

---

## HR Employee

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `hremployee@hrms.com` | `password123` | HR Employee | ESS Portal only (same as Employee) |

**Features:**
- Redirected to Employee Self Service (ESS) portal
- Same access as a regular Employee
- No admin panel access

---

## Manager

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `manager@hrms.com` | `password123` | Manager | Team oversight – view employees, approve leaves & expenses |

**Features:**
- Access to Admin Dashboard
- **Employees** – view only
- **Employee Tasks** – create and assign tasks
- **Attendance** – view only
- **Leaves** – view and approve
- **Expenses** – view and approve
- **Training** – view only
- **Travel** – view and approve
- **Performance** – view and manage

---

## Finance

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `finance@hrms.com` | `password123` | Finance | Payroll and expense management |

**Features:**
- Access to Admin Dashboard
- **Employees** – view only
- **Payroll** – view, create, update, run payroll
- **Expenses** – view, approve, process reimbursements
- **Attendance** – view only

---

## Recruiter

| Email | Password | Role | Access |
|-------|----------|------|--------|
| `recruiter@hrms.com` | `password123` | Recruiter | Employee onboarding and recruitment |

**Features:**
- Access to Admin Dashboard
- **Employees** – view, create, update (no delete)
- **Settings** – manage departments, designations, locations

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
2. **Super Admin:** `admin@hrms.com` / `password123`
3. **HR Admin:** `hradmin@hrms.com` / `password123`
4. **HR Manager:** `hrmanager@hrms.com` / `password123`
5. **Manager:** `manager@hrms.com` / `password123`
6. **Finance:** `finance@hrms.com` / `password123`
7. **Recruiter:** `recruiter@hrms.com` / `password123`

### For Testing Employee Features:
1. Go to: `http://localhost/login` (or your app URL)
2. **HR Employee:** `hremployee@hrms.com` / `password123`
3. **Any Employee:** e.g., `rajesh.kumar@hrms.com` / `password123`
4. You'll be redirected to the ESS Dashboard

---

## Who Creates ESS Tasks?

**Employee tasks** (e.g. "Complete onboarding documents", "Attend training session") are created by these roles:

| Role | Can manage tasks? | Seeded user |
|------|-------------------|-------------|
| Super Admin | Yes | `admin@hrms.com` |
| HR Admin | Yes | `hradmin@hrms.com` |
| HR Manager | Yes | `hrmanager@hrms.com` |
| Manager | Yes | `manager@hrms.com` |

Go to **Employee Tasks** (`/employee-tasks`) after login to create, edit, or assign tasks.

---

## Sample Data

Each of the 10 employees has:
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

## Troubleshooting

### If login fails:
1. Make sure database is migrated: `php artisan migrate`
2. Make sure seeders are run: `php artisan db:seed`
3. Check `.env` file database configuration
4. Clear cache: `php artisan cache:clear && php artisan config:clear`

### If a role-based user does not exist:
1. Run: `php artisan db:seed --class=DatabaseSeeder` (uses `firstOrCreate`, safe to re-run)
2. All role-based users will be created automatically

### If employee can't see ESS portal:
1. Verify user has 'Employee' role: `php artisan tinker` → `User::find(1)->roles`
2. Verify employee record exists: `User::find(1)->employee`
3. Check routes: `php artisan route:list | grep ess`
