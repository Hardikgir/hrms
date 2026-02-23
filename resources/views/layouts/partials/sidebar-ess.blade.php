<aside class="main-sidebar sidebar-dark-primary elevation-4"
    style="{{ $sidebarColor ? 'background-color: ' . $sidebarColor . ' !important;' : '' }}">
    <a href="{{ route('ess.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">{{ __('messages.employee_portal') }}</span>
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
                    <a href="{{ route('ess.dashboard') }}"
                        class="nav-link {{ request()->routeIs('ess.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('messages.dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.profile') }}"
                        class="nav-link {{ request()->routeIs('ess.profile*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>{{ __('messages.my_profile') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.tasks') }}"
                        class="nav-link {{ request()->routeIs('ess.tasks') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>{{ __('messages.tasks') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.attendance') }}"
                        class="nav-link {{ request()->routeIs('ess.attendance') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>{{ __('messages.attendance') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.leaves') }}"
                        class="nav-link {{ request()->routeIs('ess.leaves*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-times"></i>
                        <p>{{ __('messages.leaves') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.goals') }}"
                        class="nav-link {{ request()->routeIs('ess.goals') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullseye"></i>
                        <p>{{ __('messages.goals') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.reviews') }}"
                        class="nav-link {{ request()->routeIs('ess.reviews') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>{{ __('messages.performance_reviews') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.expenses') }}"
                        class="nav-link {{ request()->routeIs('ess.expenses*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>{{ __('messages.expenses') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.training') }}"
                        class="nav-link {{ request()->routeIs('ess.training') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>{{ __('messages.training') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.roster') }}"
                        class="nav-link {{ request()->routeIs('ess.roster') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>{{ __('messages.my_roster') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.assets') }}"
                        class="nav-link {{ request()->routeIs('ess.assets') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-laptop"></i>
                        <p>{{ __('messages.my_assets') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.travel') }}"
                        class="nav-link {{ request()->routeIs('ess.travel*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plane"></i>
                        <p>{{ __('messages.travel') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.exit') }}"
                        class="nav-link {{ request()->routeIs('ess.exit*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>{{ __('messages.exit') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ess.payslips') }}"
                        class="nav-link {{ request()->routeIs('ess.payslips*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>{{ __('messages.payslips') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>