@extends('layouts.adminlte')

@section('title', 'Leaves')
@section('page_title', 'Leave Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Leaves</li>
@endsection

@section('content')
    @livewire('leave.leave-index-page')
@endsection
