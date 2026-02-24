<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.choose_portal') }} - {{ __('messages.hrms') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- RTL Layout Overrides -->
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content">
            <div class="container">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> {{ __('messages.no_portal_access') }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">{{ __('messages.logout') }}</button>
                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>
