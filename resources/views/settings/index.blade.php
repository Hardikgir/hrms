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
</div>
@endsection
