@extends('layouts.adminlte')

@section('title', 'Settings')
@section('page_title', 'Settings')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="row">
    @can('manage expense categories')
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-tags mr-2"></i>Expense Categories</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage expense categories used when employees submit expenses.</p>
                <a href="{{ route('expense-categories.index') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Manage Expense Categories
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('manage asset types')
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-laptop mr-2"></i>Asset Types</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage asset types (e.g. Laptop, Phone) used when creating assets.</p>
                <a href="{{ route('asset-types.index') }}" class="btn btn-info">
                    <i class="fas fa-cog"></i> Manage Asset Types
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('manage departments')
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sitemap mr-2"></i>Departments</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage departments (e.g. Finance, IT) used in employee job details.</p>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-cog"></i> Manage Departments
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('manage designations')
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i>Designations</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage designations (e.g. Developer, Manager) used in employee job details.</p>
                <a href="{{ route('designations.index') }}" class="btn btn-info">
                    <i class="fas fa-cog"></i> Manage Designations
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('manage locations')
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-map-marker-alt mr-2"></i>Locations</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage locations (e.g. Branch Office, Head Office) used in employee job details.</p>
                <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-cog"></i> Manage Locations
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('manage employment types')
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-briefcase mr-2"></i>Employment Types</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage employment types (e.g. Full Time, Part Time) used when creating employees.</p>
                <a href="{{ route('employment-types.index') }}" class="btn btn-success">
                    <i class="fas fa-cog"></i> Manage Employment Types
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('manage employment statuses')
    <div class="col-md-6 col-lg-4">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>Employment Statuses</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage employment statuses (e.g. Active, Inactive) used when creating employees.</p>
                <a href="{{ route('employment-statuses.index') }}" class="btn btn-warning">
                    <i class="fas fa-cog"></i> Manage Employment Statuses
                </a>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection
