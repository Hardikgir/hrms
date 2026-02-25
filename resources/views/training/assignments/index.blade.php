@extends('layouts.adminlte')
@section('title', 'Training Assignments')
@section('page_title', 'Training Assignments')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Training Assignments</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Training Assignments</h3>
        <div class="card-tools">
            @can('create', \App\Modules\Training\Models\TrainingAssignment::class)
            <a href="{{ route('training.assignments.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Assign</a>
            @endcan
            <a href="{{ route('training.courses.index') }}" class="btn btn-secondary btn-sm">Courses</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="employee_id" class="form-control">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Filter</button></div>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead><tr><th>Employee</th><th>Course</th><th>Status</th><th>Assigned</th><th>Completed</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($assignments as $a)
                <tr>
                    <td>{{ $a->employee->full_name ?? '-' }}</td>
                    <td>{{ $a->course->name ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$a->status)) }}</td>
                    <td>{{ $a->assigned_at?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $a->completed_at?->format('d M Y') ?? '-' }}</td>
                    <td class="action-buttons">
                        @if($a->status !== 'completed' && auth()->user()->can('manage training'))
                        <form action="{{ route('training.assignments.complete', $a) }}" method="POST" class="d-inline">@csrf<input type="number" name="score" min="0" max="100" placeholder="{{ __('messages.placeholder_score') }}" class="form-control form-control-sm d-inline-block w-auto"> <button type="submit" class="btn btn-sm btn-success">{{ __('messages.mark_complete') }}</button></form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No assignments.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $assignments->links() }}
    </div>
</div>
@endsection
