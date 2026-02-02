@extends('layouts.adminlte')
@section('title', 'Exit Requests')
@section('page_title', 'Exit Management')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Exit</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Exit Requests</h3>
        <div class="card-tools">
            @if(auth()->user()->employee)
            <a href="{{ route('exit.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Submit Resignation</a>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>@endif
        @if($employees->isNotEmpty())
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3"><select name="employee_id" class="form-control"><option value="">All</option>@foreach($employees as $emp)<option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="status" class="form-control"><option value="">All</option><option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option><option value="clearance" {{ request('status') == 'clearance' ? 'selected' : '' }}>Clearance</option><option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option></select></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Filter</button></div>
            </div>
        </form>
        @endif
        <table class="table table-bordered table-striped">
            <thead><tr><th>Employee</th><th>Resignation Date</th><th>Last Working</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($exits as $e)
                <tr>
                    <td>{{ $e->employee->full_name ?? '-' }}</td>
                    <td>{{ $e->resignation_date->format('d M Y') }}</td>
                    <td>{{ $e->last_working_date->format('d M Y') }}</td>
                    <td>{{ $e->reason ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$e->status)) }}</td>
                    <td><a href="{{ route('exit.show', $e) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No exit requests.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $exits->links() }}
    </div>
</div>
@endsection
