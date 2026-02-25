@extends('layouts.adminlte')

@section('title', __('messages.run_payroll'))
@section('page_title', __('messages.run_payroll'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">{{ __('messages.payroll') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.run_payroll') }}</li>
@endsection

@section('content')
    @livewire('payroll.payroll-run-page')
@endsection