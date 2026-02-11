@extends('layouts.adminlte')

@section('title', __('messages.settings'))
@section('page_title', __('messages.settings'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.settings') }}</li>
@endsection

@section('content')
    <div class="row">
        @can('manage expense categories')
            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-tags mr-2"></i>{{ __('messages.expense_categories') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('messages.expense_categories_desc') }}</p>
                        <a href="{{ route('expense-categories.index') }}" class="btn btn-primary">
                            <i class="fas fa-cog"></i> {{ __('messages.manage_expense_categories') }}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('manage asset types')
            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-laptop mr-2"></i>{{ __('messages.asset_types') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('messages.asset_types_desc') }}</p>
                        <a href="{{ route('asset-types.index') }}" class="btn btn-info">
                            <i class="fas fa-cog"></i> {{ __('messages.manage_asset_types') }}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('manage departments')
            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-sitemap mr-2"></i>{{ __('messages.departments') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('messages.departments_desc') }}</p>
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-cog"></i> {{ __('messages.manage_departments') }}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('manage designations')
            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i>{{ __('messages.designations') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('messages.designations_desc') }}</p>
                        <a href="{{ route('designations.index') }}" class="btn btn-info">
                            <i class="fas fa-cog"></i> {{ __('messages.manage_designations') }}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('manage locations')
            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-map-marker-alt mr-2"></i>{{ __('messages.locations') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('messages.locations_desc') }}</p>
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-cog"></i> {{ __('messages.manage_locations') }}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('manage employment types')
            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-briefcase mr-2"></i>{{ __('messages.employment_types') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('messages.employment_types_desc') }}</p>
                        <a href="{{ route('employment-types.index') }}" class="btn btn-success">
                            <i class="fas fa-cog"></i> {{ __('messages.manage_employment_types') }}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('manage employment statuses')
            <div class="col-md-6 col-lg-4">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>{{ __('messages.employment_statuses') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ __('messages.employment_statuses_desc') }}</p>
                        <a href="{{ route('employment-statuses.index') }}" class="btn btn-warning">
                            <i class="fas fa-cog"></i> {{ __('messages.manage_employment_statuses') }}
                        </a>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection