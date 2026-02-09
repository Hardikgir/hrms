@extends('layouts.adminlte')

@section('title', 'Departments')
@section('page_title', 'Departments')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Departments</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Departments</h3>
        <div class="card-tools">
            <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Department</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Departments listed here appear in the employee job details dropdown.</p>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Parent</th>
                    <th>Managers</th>
                    <th>Employees</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                <tr>
                    <td>{{ $dept->id }}</td>
                    <td>{{ $dept->name }}</td>
                    <td><code>{{ $dept->code ?? '-' }}</code></td>
                    <td>{{ $dept->parent?->name ?? '-' }}</td>
                    <td>
                        @forelse($dept->managers as $manager)
                            <span class="badge badge-primary">{{ $manager->full_name }}</span>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                    </td>
                    <td><span class="badge badge-info">{{ $dept->employees_count }}</span></td>
                    <td>
                        @if($dept->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('departments.edit', $dept) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No departments. <a href="{{ route('departments.create') }}">Add one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $departments->links() }}</div>
    </div>
</div>
@endsection
