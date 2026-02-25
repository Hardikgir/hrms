@extends('layouts.adminlte')

@section('title', __('messages.payroll'))
@section('page_title', __('messages.payroll_management'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.payroll') }}</li>
@endsection

@section('content')
    @livewire('payroll.payroll-index-page')
@endsection