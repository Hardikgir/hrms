# Role-Based Access Control (RBAC)

## Department-Based Roles

Each department can have similar role types: **Admin**, **Manager**, **Employee**. Super Admin controls what each role can do by assigning **permissions** per role. Permissions determine:

- **Page access**: which routes and actions the role can use (via `can:permission` middleware and policies).
- **Sidebar visibility**: the admin sidebar shows menu items only when the user has the matching permission (e.g. “Employees” requires `view employees`).

So: **one set of permissions** drives both access and sidebar. Super Admin chooses which permissions each role gets in **Settings → Roles → Edit role**.

## Creating Roles for a Department

1. **Settings → Roles** (requires “manage roles”).
2. **Filter by department** (optional) to see only that department’s roles.
3. **Create default roles for a department**: choose a department and click Create. This adds three roles: “{Department} Admin”, “{Department} Manager”, “{Department} Employee” with no permissions.
4. **Edit each role** and assign the permissions that role should have. Those permissions define which pages and sidebar items the role sees.

Roles can optionally be linked to a **department** and **role type** (Admin / Manager / Employee) for organization; the actual access is entirely from the permissions you assign.

## HR Department (Example)

| Role         | Typical use |
|-------------|-------------|
| **HR Admin**   | Full HR: employees CRUD, attendance, leaves, tasks, performance, expenses, training, shifts, assets, travel, exit, payroll, and Settings (departments, designations, locations, employment types/statuses, expense categories, asset types). Not “manage roles”. |
| **HR Manager** | View and approve: view employees, attendance, approve leaves, manage tasks, performance, approve expenses, view training, shifts, assets (approve returns), travel, exit, view payroll. No Settings, no run payroll, no create/update/delete employees. |
| **HR Employee** | ESS only (like Employee): profile, leaves, attendance, payslips, etc. No admin panel. Dashboard redirects to ESS when user has an employee record. |

Other departments (IT, Finance, etc.) can have their own Admin / Manager / Employee roles with different permission sets chosen by Super Admin.

## Other Roles

- **Super Admin**: All permissions, including manage roles. Cannot be deleted or renamed.
- **Employee**: ESS only; no admin permissions.
- **Manager**, **Finance**, **Recruiter**: Assign permissions via Settings → Roles as needed.

## Implementation

- **Permissions** are enforced via Laravel `can()` and `@can`, and route middleware `can:permission`.
- **Policies** use permissions for authorize checks.
- **Sidebar**: Each menu item is shown only if the user has the corresponding permission (e.g. “view employees”, “view attendance”).
- **Dashboard**: Users with role **Employee** or **HR Employee** (or any role with only ESS) and an employee record are redirected to ESS dashboard (`/ess/dashboard`).
- **Settings**: Access when the user has at least one “manage” permission; each settings card is shown based on its permission.

## Applying Roles (New / Existing Install)

- **Fresh install**: Run `php artisan db:seed` (creates default roles and HR roles).
- **Existing install**: Run `php artisan migrate` to add `department_id` and `role_type` to roles and link existing HR roles to the HR department.

Assign roles to users via **Settings → User Roles** (requires “manage roles”).
