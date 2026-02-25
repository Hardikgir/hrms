@extends('layouts.adminlte')

@section('title', 'Attendance')
@section('page_title', 'Attendance Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Attendance</li>
@endsection

@section('content')
    @livewire('attendance.attendance-index-page')
@endsection
