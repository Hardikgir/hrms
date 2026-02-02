@extends('layouts.adminlte')

@section('title', 'Payroll')
@section('page_title', 'Payroll Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Payroll</li>
@endsection

@section('content')
    @livewire('payroll.payroll-index-page')
@endsection
