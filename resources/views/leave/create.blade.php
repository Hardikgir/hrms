@extends('layouts.adminlte')

@section('title', 'Apply Leave')
@section('page_title', 'Apply Leave')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leaves</a></li>
    <li class="breadcrumb-item active">Apply Leave</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Apply for Leave</h3>
    </div>
    <div class="card-body">
        @livewire('leave.leave-application-form')
    </div>
</div>
@endsection
