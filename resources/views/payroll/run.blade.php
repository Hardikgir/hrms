@extends('layouts.adminlte')

@section('title', 'Run Payroll')
@section('page_title', 'Run Payroll')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li>
    <li class="breadcrumb-item active">Run Payroll</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Run Payroll</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">Payroll processing functionality coming soon...</p>
    </div>
</div>
@endsection

