@extends('layouts.adminlte')

@section('title', 'Goals (KRA/OKR)')
@section('page_title', 'Goals')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.index') }}">Performance</a></li>
    <li class="breadcrumb-item active">Goals</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Goals (KRA / OKR)</h3>
        <div class="card-tools">
            @can('manage performance')
            <a href="{{ route('performance.goals.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Goal</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('performance.goals.index') }}" class="mb-3">
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
                    <select name="cycle_id" class="form-control">
                        <option value="">All Cycles</option>
                        @foreach($cycles as $c)
                            <option value="{{ $c->id }}" {{ request('cycle_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="achieved" {{ request('status') == 'achieved' ? 'selected' : '' }}>Achieved</option>
                        <option value="not_achieved" {{ request('status') == 'not_achieved' ? 'selected' : '' }}>Not Achieved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Target</th>
                    <th>Weight</th>
                    <th>Status</th>
                    <th>Cycle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goals as $goal)
                <tr>
                    <td>{{ $goal->employee->full_name ?? '-' }}</td>
                    <td><span class="badge badge-{{ $goal->type === 'kra' ? 'primary' : 'info' }}">{{ strtoupper($goal->type) }}</span></td>
                    <td>{{ Str::limit($goal->title, 40) }}</td>
                    <td>{{ $goal->target_value ?? '-' }}</td>
                    <td>{{ $goal->weight }}</td>
                    <td>
                        @if($goal->status === 'active')<span class="badge badge-warning">Active</span>
                        @elseif($goal->status === 'achieved')<span class="badge badge-success">Achieved</span>
                        @elseif($goal->status === 'not_achieved')<span class="badge badge-danger">Not Achieved</span>
                        @else<span class="badge badge-secondary">{{ $goal->status }}</span>
                        @endif
                    </td>
                    <td>{{ $goal->cycle->name ?? '—' }}</td>
                    <td>
                        @can('update', $goal)
                        <a href="{{ route('performance.goals.edit', $goal) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $goal)
                        <form action="{{ route('performance.goals.destroy', $goal) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this goal?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">No goals found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $goals->links() }}</div>
    </div>
</div>
@endsection
