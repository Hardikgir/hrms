<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Portal') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    @livewireStyles
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="{{ route('ess.profile') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
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
                        <a href="{{ route('ess.leaves') }}" class="nav-link {{ request()->routeIs('ess.leaves') ? 'active' : '' }}">
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

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page_title', 'Employee Portal')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumbs')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} {{ config('app.name') }}.</strong>
        All rights reserved.
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@livewireScripts
@stack('scripts')
</body>
</html>

