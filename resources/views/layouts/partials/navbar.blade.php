<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Language Toggle Switch -->
        <li class="nav-item d-flex align-items-center mr-3">
            <div class="lang-toggle-box d-flex overflow-hidden border"
                style="border-radius: 4px; border-color: #dee2e6 !important;">
                <a href="{{ route('language.switch', 'en') }}"
                    class="px-3 py-1 text-sm font-weight-bold text-decoration-none transition"
                    style="{{ app()->getLocale() == 'en' ? 'background-color: ' . ($sidebarColor ?? '#343a40') . ' !important; color: #fff !important;' : 'background-color: transparent; color: #6c757d;' }}">
                    English
                </a>
                <a href="{{ route('language.switch', 'ar') }}"
                    class="px-3 py-1 text-sm font-weight-bold text-decoration-none transition"
                    style="{{ app()->getLocale() == 'ar' ? 'background-color: ' . ($sidebarColor ?? '#343a40') . ' !important; color: #fff !important;' : 'background-color: transparent; color: #6c757d;' }}">
                    Arabic
                </a>
            </div>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-user"></i>
                <span class="d-none d-md-inline ml-1">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @if(Auth::user()->hasRole('Employee') && Auth::user()->employee)
                    <a href="{{ route('ess.profile') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> {{ __('messages.profile') }}
                    </a>
                @endif
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('messages.logout') }}
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>