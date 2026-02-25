@extends('layouts.adminlte')

@section('title', __('messages.employee_tasks'))
@section('page_title', __('messages.employee_tasks'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.employee_tasks') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.task_list') }}</h3>
            <div class="card-tools">
                @can('manage tasks')
                    <a href="{{ route('employee-tasks.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('messages.add_task') }}
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('employee-tasks.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <select name="employee_id" class="form-control">
                            <option value="">{{ __('messages.all_employees') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }} ({{ $emp->employee_id ?? $emp->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                {{ __('messages.pending') }}
                            </option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                                {{ __('messages.in_progress') }}
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                {{ __('messages.completed') }} ({{ __('messages.awaiting_approval') }})
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                {{ __('messages.approved') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                        <a href="{{ route('employee-tasks.index') }}"
                            class="btn btn-secondary">{{ __('messages.reset') }}</a>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.tasks') }}</th>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.due_date') }}</th>
                        <th>{{ __('messages.priority') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.created_by') }}</th>
                        <th>{{ __('messages.actions') }}</th>
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
                            <td>{{ $task->employee ? $task->employee->full_name . ' (' . ($task->employee->employee_id ?? '-') . ')' : __('messages.all_unassigned') }}
                            </td>
                            <td>{{ $task->due_date->format('d M Y') }}</td>
                            <td>
                                @if($task->priority === 'high')
                                    <span class="badge badge-danger">{{ __('messages.high') }}</span>
                                @elseif($task->priority === 'medium')
                                    <span class="badge badge-warning">{{ __('messages.medium') }}</span>
                                @else
                                    <span class="badge badge-info">{{ __('messages.low') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($task->status === 'approved')
                                    <span class="badge badge-secondary">{{ __('messages.approved') }}</span>
                                    @if($task->approved_at)
                                        <br><small class="text-muted">{{ $task->approved_at->format('d M Y') }} {{ __('messages.by') }}
                                            {{ $task->approvedBy->name ?? '-' }}</small>
                                    @endif
                                @elseif($task->status === 'completed')
                                    <span class="badge badge-success">{{ __('messages.completed') }}</span>
                                    @if($task->completed_at)
                                        <br><small class="text-muted">{{ $task->completed_at->format('d M Y H:i') }}</small>
                                    @endif
                                @elseif($task->status === 'in_progress')
                                    <span class="badge badge-primary">{{ __('messages.in_progress') }}</span>
                                @elseif($task->status === 'pending')
                                    <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                                @endif
                            </td>
                            <td>{{ $task->createdBy->name ?? '-' }}</td>
                            <td class="action-buttons">
                                @if($task->status === 'completed')
                                    <form action="{{ route('employee-tasks.approve', $task) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                            title="{{ __('messages.approve_task_help') }}">
                                            <i class="fas fa-check"></i> {{ __('messages.approve') }}
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('employee-tasks.edit', $task) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('employee-tasks.destroy', $task) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('messages.delete_this_task') }}');">
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
                            <td colspan="8" class="text-center">{{ __('messages.no_tasks_found') }}</td>
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