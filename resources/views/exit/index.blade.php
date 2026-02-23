@extends('layouts.adminlte')
@section('title', __('messages.exit_requests'))
@section('page_title', __('messages.exit_management'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.exit') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.exit_requests') }}</h3>
            <div class="card-tools">
                @if(auth()->user()->employee)
                    <a href="{{ route('exit.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                        {{ __('messages.submit_resignation') }}</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($employees->isNotEmpty())
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3"><select name="employee_id" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>@foreach($employees as $emp)<option
                                    value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }}</option>@endforeach
                            </select></div>
                        <div class="col-md-2"><select name="status" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                                <option value="clearance" {{ request('status') == 'clearance' ? 'selected' : '' }}>
                                    {{ __('messages.clearance') }}</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    {{ __('messages.completed') }}</option>
                            </select></div>
                        <div class="col-md-2"><button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                        </div>
                    </div>
                </form>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.resignation_date') }}</th>
                        <th>{{ __('messages.last_working') }}</th>
                        <th>{{ __('messages.reason') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exits as $e)
                        <tr>
                            <td>{{ $e->employee->full_name ?? '-' }}</td>
                            <td>{{ $e->resignation_date->format('d M Y') }}</td>
                            <td>{{ $e->last_working_date->format('d M Y') }}</td>
                            <td>{{ $e->reason ?? '-' }}</td>
                            <td>{{ __('messages.' . $e->status) }}</td>
                            <td class="action-buttons"><a href="{{ route('exit.show', $e) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_exit_requests') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $exits->links() }}
        </div>
    </div>
@endsection