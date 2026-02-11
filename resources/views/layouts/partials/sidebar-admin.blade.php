{{-- Admin sidebar only. Employees use layouts.ess with sidebar-ess. --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4"
    style="{{ $sidebarColor ? 'background-color: ' . $sidebarColor . ' !important;' : '' }}">
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
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('messages.dashboard') }}</p>
                    </a>
                </li>
                @can('view employees')
                    <li class="nav-item">
                        <a href="{{ route('employees.index') }}"
                            class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('messages.employees') }}</p>
                        </a>
                    </li>
                @endcan
                @can('manage tasks')
                    <li class="nav-item">
                        <a href="{{ route('employee-tasks.index') }}"
                            class="nav-link {{ request()->routeIs('employee-tasks.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>{{ __('messages.employee_tasks') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view attendance')
                    <li class="nav-item">
                        <a href="{{ route('attendance.index') }}"
                            class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>{{ __('messages.attendance') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view leaves')
                    <li class="nav-item">
                        <a href="{{ route('leaves.index') }}"
                            class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-times"></i>
                            <p>{{ __('messages.leaves') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view payroll')
                    <li class="nav-item">
                        <a href="{{ route('payroll.index') }}"
                            class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>{{ __('messages.payroll') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view performance')
                    <li class="nav-item">
                        <a href="{{ route('performance.cycles.index') }}"
                            class="nav-link {{ request()->routeIs('performance.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>{{ __('messages.performance') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view expenses')
                    <li class="nav-item">
                        <a href="{{ route('expenses.index') }}"
                            class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>{{ __('messages.expenses') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view training')
                    <li class="nav-item">
                        <a href="{{ route('training.courses.index') }}"
                            class="nav-link {{ request()->routeIs('training.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>{{ __('messages.training') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view shifts')
                    <li class="nav-item">
                        <a href="{{ route('shifts.index') }}"
                            class="nav-link {{ request()->routeIs('shifts.*') || request()->routeIs('roster.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>{{ __('messages.shifts_roster') }}</p>
                        </a>
                    </li>
                @endcan
                @can('view assets')
                    <li class="nav-item">
                        <a href="{{ route('assets.index') }}"
                            class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-laptop"></i>
                            <p>{{ __('messages.assets') }}</p>
                        </a>
                    </li>
                @endcan
                @if(auth()->user()->can('manage expense categories') || auth()->user()->can('manage asset types'))
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}"
                            class="nav-link {{ request()->routeIs('settings.*') || request()->routeIs('expense-categories.*') || request()->routeIs('asset-types.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>{{ __('messages.settings') }}</p>
                        </a>
                    </li>
                @endif
                @can('view exit')
                    <li class="nav-item">
                        <a href="{{ route('exit.index') }}"
                            class="nav-link {{ request()->routeIs('exit.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-door-open"></i>
                            <p>{{ __('messages.exit') }}</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>