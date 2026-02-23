<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.employee_portal_title')) - {{ config('app.name', 'Laravel') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        /* Consistent spacing: action buttons and card toolbar */
        .card-tools>*:not(:first-child) {
            margin-left: 0.5rem;
        }

        .action-buttons .btn,
        .action-buttons form.d-inline,
        .action-buttons a.btn {
            margin-right: 0.35rem;
            margin-bottom: 0.25rem;
        }

        .action-buttons .form-control-sm.d-inline-block {
            margin-right: 0.35rem;
        }

        .filter-form .form-control,
        .filter-form .btn {
            margin-bottom: 0.5rem;
        }

        .filter-form .btn+.btn {
            margin-left: 0.5rem;
        }
    </style>

    @livewireStyles
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.partials.navbar')

        <!-- Main Sidebar (ESS only; admins use admin layout) -->
        @include('layouts.partials.sidebar-ess')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page_title', __('messages.employee_portal_title'))</h1>
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
                        <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert"
                            data-auto-dismiss="5000">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('messages.aria_close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show flash-alert" role="alert"
                            data-auto-dismiss="5000">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('messages.aria_close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>{{ __('messages.copyright') }} &copy; {{ date('Y') }} {{ config('app.name') }}.</strong>
            {{ __('messages.all_rights_reserved') }}
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

        // Auto-dismiss flash alerts after 5 seconds
        $(function () {
            var dismissMs = 5000;
            $('.flash-alert[data-auto-dismiss]').each(function () {
                var el = $(this);
                var ms = parseInt(el.data('auto-dismiss'), 10) || dismissMs;
                setTimeout(function () {
                    el.fadeTo(300, 0, function () {
                        el.alert('close');
                    });
                }, ms);
            });
        });
    </script>

    @livewireScripts
    @stack('scripts')
</body>

</html>