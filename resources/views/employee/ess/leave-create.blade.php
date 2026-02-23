@extends('layouts.ess')

@section('title', 'Apply for Leave')
@section('page_title', 'Apply for Leave')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves') }}">Leaves</a></li>
    <li class="breadcrumb-item active">Apply for Leave</li>
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
