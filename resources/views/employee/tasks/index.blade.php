@extends('layouts.adminlte')

@section('title', 'Employee Tasks')
@section('page_title', 'Employee Tasks')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Employee Tasks</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Task List</h3>
        <div class="card-tools">
            @can('manage tasks')
            <a href="{{ route('employee-tasks.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Task
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('employee-tasks.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="employee_id" class="form-control">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }} ({{ $emp->employee_id ?? $emp->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed (awaiting approval)</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('employee-tasks.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Employee</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>
                        <strong>{{ $task->title }}</strong>
                        @if($task->description)
                            <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                        @endif
                    </td>
                    <td>{{ $task->employee ? $task->employee->full_name . ' (' . ($task->employee->employee_id ?? '-') . ')' : 'All / Unassigned' }}</td>
                    <td>{{ $task->due_date->format('d M Y') }}</td>
                    <td>
                        @if($task->priority === 'high')
                            <span class="badge badge-danger">High</span>
                        @elseif($task->priority === 'medium')
                            <span class="badge badge-warning">Medium</span>
                        @else
                            <span class="badge badge-info">Low</span>
                        @endif
                    </td>
                    <td>
                        @if($task->status === 'approved')
                            <span class="badge badge-secondary">Approved</span>
                            @if($task->approved_at)
                                <br><small class="text-muted">{{ $task->approved_at->format('d M Y') }} by {{ $task->approvedBy->name ?? '-' }}</small>
                            @endif
                        @elseif($task->status === 'completed')
                            <span class="badge badge-success">Completed</span>
                            @if($task->completed_at)
                                <br><small class="text-muted">{{ $task->completed_at->format('d M Y H:i') }}</small>
                            @endif
                        @elseif($task->status === 'in_progress')
                            <span class="badge badge-info">In Progress</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td>{{ $task->createdBy->name ?? '-' }}</td>
                    <td>
                        @if($task->status === 'completed')
                            <form action="{{ route('employee-tasks.approve', $task) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Approve – task will disappear from employee list">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('employee-tasks.edit', $task) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('employee-tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No tasks found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection
