{{-- Employee Self-Service sidebar. Admin uses layouts.adminlte with sidebar-admin. --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('ess.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">Employee Portal</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle fa-2x text-white"></i>
            </div>
            <div class="info">
                <a href="{{ route('ess.profile') }}" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-muted">{{ Auth::user()->employee->employee_id ?? '' }}</small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-item">
                    <a href="{{ route('ess.dashboard') }}" class="nav-link {{ request()->routeIs('ess.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.profile') }}" class="nav-link {{ request()->routeIs('ess.profile*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>My Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.tasks') }}" class="nav-link {{ request()->routeIs('ess.tasks') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Tasks</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.attendance') }}" class="nav-link {{ request()->routeIs('ess.attendance') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Attendance</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.leaves') }}" class="nav-link {{ request()->routeIs('ess.leaves*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-times"></i>
                        <p>Leaves</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.goals') }}" class="nav-link {{ request()->routeIs('ess.goals') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullseye"></i>
                        <p>Goals (KRA/OKR)</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.reviews') }}" class="nav-link {{ request()->routeIs('ess.reviews') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Performance Reviews</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.expenses') }}" class="nav-link {{ request()->routeIs('ess.expenses*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Expenses</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.training') }}" class="nav-link {{ request()->routeIs('ess.training') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>Training</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.roster') }}" class="nav-link {{ request()->routeIs('ess.roster') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>My Roster</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.assets') }}" class="nav-link {{ request()->routeIs('ess.assets') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-laptop"></i>
                        <p>My Assets</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.travel') }}" class="nav-link {{ request()->routeIs('ess.travel*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plane"></i>
                        <p>Travel</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.exit') }}" class="nav-link {{ request()->routeIs('ess.exit*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>Exit</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.payslips') }}" class="nav-link {{ request()->routeIs('ess.payslips*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Payslips</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
