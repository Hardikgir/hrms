<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.choose_portal') }} - {{ __('messages.hrms') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        .portal-card { transition: transform 0.2s, box-shadow 0.2s; }
        .portal-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .portal-card .card-body { min-height: 120px; }
    </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <div class="content-header">
            <div class="container">
                <h1 class="m-0">{{ __('messages.choose_portal') }}</h1>
            </div>
        </div>
        <section class="content">
            <div class="container">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif
                <p class="text-muted mb-4">{{ __('messages.choose_portal_heading') }}</p>
                <div class="row">
                    @foreach($portals as $portal)
                        @php
                            $label = $portalService->getPortalLabel($portal);
                            $desc = $portalService->getPortalDescription($portal);
                            $icon = $portal === 'employee' ? 'fa-user' : ($portal === 'manager' ? 'fa-users-cog' : 'fa-cogs');
                            $color = $portal === 'employee' ? 'primary' : ($portal === 'manager' ? 'info' : 'success');
                        @endphp
                        <div class="col-md-4 mb-4">
                            <div class="card portal-card card-outline card-{{ $color }}">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas {{ $icon }} mr-2"></i>{{ $label }}</h5>
                                    <p class="card-text text-muted small">{{ $desc }}</p>
                                    <form method="POST" action="{{ route('portal.enter') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="portal" value="{{ $portal }}">
                                        <button type="submit" class="btn btn-{{ $color }}">
                                            {{ __('messages.continue_as', ['portal' => $label]) }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary"><i class="fas fa-sign-out-alt mr-1"></i> {{ __('messages.logout') }}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
