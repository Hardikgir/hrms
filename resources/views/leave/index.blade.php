@extends('layouts.adminlte')

@section('title', __('messages.leaves'))
@section('page_title', __('messages.leave_management'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.leaves') }}</li>
@endsection

@section('content')
    @livewire('leave.leave-index-page')
@endsection