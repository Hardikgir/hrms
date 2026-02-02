@extends('layouts.adminlte')

@section('title', 'Run Payroll')
@section('page_title', 'Run Payroll')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li>
    <li class="breadcrumb-item active">Run Payroll</li>
@endsection

@section('content')
    @livewire('payroll.payroll-run-page')
@endsection
