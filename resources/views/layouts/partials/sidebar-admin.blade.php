{{-- Admin sidebar only. Employees use layouts.ess with sidebar-ess. --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">HRMS</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name ?? 'User' }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Employees</p>
                    </a>
                </li>
                @can('manage tasks')
                <li class="nav-item">
                    <a href="{{ route('employee-tasks.index') }}" class="nav-link {{ request()->routeIs('employee-tasks.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Employee Tasks</p>
                    </a>
                </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Attendance</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-times"></i>
                        <p>Leaves</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('payroll.index') }}" class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Payroll</p>
                    </a>
                </li>
                @can('view performance')
                <li class="nav-item">
                    <a href="{{ route('performance.cycles.index') }}" class="nav-link {{ request()->routeIs('performance.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Performance</p>
                    </a>
                </li>
                @endcan
                @can('view expenses')
                <li class="nav-item">
                    <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Expenses</p>
                    </a>
                </li>
                @endcan
                @can('manage expense categories')
                <li class="nav-item">
                    <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Expense Categories</p>
                    </a>
                </li>
                @endcan
                @can('view training')
                <li class="nav-item">
                    <a href="{{ route('training.courses.index') }}" class="nav-link {{ request()->routeIs('training.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>Training</p>
                    </a>
                </li>
                @endcan
                @can('view shifts')
                <li class="nav-item">
                    <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.*') || request()->routeIs('roster.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Shifts & Roster</p>
                    </a>
                </li>
                @endcan
                @can('view assets')
                <li class="nav-item">
                    <a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-laptop"></i>
                        <p>Assets</p>
                    </a>
                </li>
                @endcan
                @can('view exit')
                <li class="nav-item">
                    <a href="{{ route('exit.index') }}" class="nav-link {{ request()->routeIs('exit.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>Exit</p>
                    </a>
                </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>
