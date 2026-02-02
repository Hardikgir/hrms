# HRMS – All User Types for Testing

Use this document to test the application with every role. **Default password for all users:** `password123`

---

## 1. Super Admin

| Field | Value |
|-------|--------|
| **Email** | `admin@hrms.com` |
| **Password** | `password123` |
| **Role** | Super Admin |
| **Seeded** | Yes |

**Access:** Full access to all modules and permissions.

**Use for testing:**
- Admin dashboard
- Employees (CRUD)
- Employee Tasks (create/assign tasks)
- Attendance (view all, check-in/out)
- Leaves (view all, approve/reject)
- Payroll (view, run, lock, approve)
- Performance (cycles, goals, reviews)
- Expenses (view all, approve, mark reimbursed)
- Training (courses, assignments)
- Shifts & Roster
- Assets
- Travel (view all, approve, complete)
- Exit (view all, update status, checklist, settlement)

**URL after login:** `/dashboard` (admin)

---

## 2. HR Admin

| Field | Value |
|-------|--------|
| **Email** | *(no user seeded by default)* |
| **Password** | — |
| **Role** | HR Admin |
| **Seeded** | No |

**Access:** View/create/update employees, view attendance, view/approve leaves, manage tasks, view & manage performance, view/approve expenses, view & manage training, view & manage shifts, view & manage assets, view/approve travel, view & manage exit. **No:** delete employees, run payroll, process reimbursements.

**To create a test user (e.g. Tinker or seeder):**
```php
$user = User::firstOrCreate(
    ['email' => 'hradmin@hrms.com'],
    ['name' => 'HR Admin', 'password' => bcrypt('password123'), 'is_active' => true]
);
$user->assignRole('HR Admin');
```

**Use for testing:** Same as Super Admin except no payroll run, no expense reimbursement, no delete employees.

---

## 3. Manager

| Field | Value |
|-------|--------|
| **Email** | *(no user seeded by default)* |
| **Password** | — |
| **Role** | Manager |
| **Seeded** | No |

**Access:** Role exists; permissions are not assigned in the default seeder. Assign permissions as needed (e.g. view employees, view attendance, approve leaves).

**To create a test user:**
```php
$user = User::firstOrCreate(
    ['email' => 'manager@hrms.com'],
    ['name' => 'Manager', 'password' => bcrypt('password123'), 'is_active' => true]
);
$user->assignRole('Manager');
// Optional: $user->givePermissionTo(['view employees', 'view attendance', 'approve leaves']);
```

**Use for testing:** Manager-level flows (team view, leave approval) after granting the right permissions.

---

## 4. Finance

| Field | Value |
|-------|--------|
| **Email** | *(no user seeded by default)* |
| **Password** | — |
| **Role** | Finance |
| **Seeded** | No |

**Access:** Role exists; permissions are not assigned in the default seeder. Typically would get payroll and expense reimbursement permissions.

**To create a test user:**
```php
$user = User::firstOrCreate(
    ['email' => 'finance@hrms.com'],
    ['name' => 'Finance', 'password' => bcrypt('password123'), 'is_active' => true]
);
$user->assignRole('Finance');
// Optional: $user->givePermissionTo(['view payroll', 'create payroll', 'update payroll', 'view expenses', 'process reimbursements']);
```

**Use for testing:** Payroll and expense reimbursement after granting permissions.

---

## 5. Recruiter

| Field | Value |
|-------|--------|
| **Email** | *(no user seeded by default)* |
| **Password** | — |
| **Role** | Recruiter |
| **Seeded** | No |

**Access:** Role exists; permissions are not assigned in the default seeder. Would be used when recruitment module and permissions are added.

**To create a test user:**
```php
$user = User::firstOrCreate(
    ['email' => 'recruiter@hrms.com'],
    ['name' => 'Recruiter', 'password' => bcrypt('password123'), 'is_active' => true]
);
$user->assignRole('Recruiter');
```

**Use for testing:** Recruitment-related features once permissions and module exist.

---

## 6. Employee (ESS)

| Field | Value |
|-------|--------|
| **Email** | See table below |
| **Password** | `password123` |
| **Role** | Employee |
| **Seeded** | Yes (10 users) |

**Access:** Employee Self Service (ESS) only. Can view/edit own profile, tasks, attendance (check-in/out), leaves (apply/view), payslips, goals, reviews, expenses (submit/view own), training (view assignments), roster (view own), assets (view assigned), travel (submit/view own), exit (submit/view own).

**URL after login:** `/ess/dashboard`

### Seeded employee test users

| Employee ID | Name           | Email                     | Password    |
|-------------|----------------|---------------------------|-------------|
| EMP001      | Rajesh Kumar   | `rajesh.kumar@hrms.com`   | `password123` |
| EMP002      | Priya Sharma   | `priya.sharma@hrms.com`   | `password123` |
| EMP003      | Amit Patel     | `amit.patel@hrms.com`     | `password123` |
| EMP004      | Sneha Singh    | `sneha.singh@hrms.com`    | `password123` |
| EMP005      | Vikram Reddy   | `vikram.reddy@hrms.com`   | `password123` |
| EMP006      | Anjali Mehta   | `anjali.mehta@hrms.com`   | `password123` |
| EMP007      | Rahul Verma    | `rahul.verma@hrms.com`    | `password123` |
| EMP008      | Kavita Joshi   | `kavita.joshi@hrms.com`   | `password123` |
| EMP009      | Suresh Iyer    | `suresh.iyer@hrms.com`    | `password123` |
| EMP010      | Meera Nair     | `meera.nair@hrms.com`     | `password123` |

**Use for testing:** ESS flows (profile, tasks, attendance, leaves, payslips, goals, reviews, expenses, training, roster, assets, travel, exit).

---

## Summary

| User type   | Seeded user          | Email (if seeded)     | Purpose for testing        |
|------------|----------------------|------------------------|----------------------------|
| Super Admin| Yes                  | `admin@hrms.com`      | Full admin and all modules |
| HR Admin   | No                   | —                      | HR scope without payroll/finance |
| Manager    | No                   | —                      | Manager flows (after permissions) |
| Finance    | No                   | —                      | Payroll & reimbursements   |
| Recruiter  | No                   | —                      | Recruitment (when available) |
| Employee   | Yes (10 users)       | e.g. `rajesh.kumar@hrms.com` | ESS and self-service |

---

## Quick test checklist

- **Super Admin:** Log in as `admin@hrms.com` → open each sidebar menu and run one action per module.
- **Employee:** Log in as `rajesh.kumar@hrms.com` → open ESS menu (Tasks, Attendance, Leaves, Goals, Reviews, Expenses, Training, Roster, Assets, Travel, Exit, Payslips).
- **Other roles:** Create users as above, assign role and permissions, then repeat for that role’s scope.

---

## Security note

These credentials are for **testing only**. Change all passwords and restrict access in production.
