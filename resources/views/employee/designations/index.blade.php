@extends('layouts.adminlte')

@section('title', 'Designations')
@section('page_title', 'Designations')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Designations</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Designations</h3>
        <div class="card-tools">
            <a href="{{ route('designations.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Designation</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Designations listed here appear in the employee job details dropdown.</p>
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
                    <th>Department</th>
                    <th>Employees</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($designations as $desig)
                <tr>
                    <td>{{ $desig->id }}</td>
                    <td>{{ $desig->name }}</td>
                    <td><code>{{ $desig->code ?? '-' }}</code></td>
                    <td>{{ $desig->department?->name ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $desig->employees_count }}</span></td>
                    <td>
                        @if($desig->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('designations.edit', $desig) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('designations.destroy', $desig) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this designation?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No designations. <a href="{{ route('designations.create') }}">Add one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $designations->links() }}</div>
    </div>
</div>
@endsection
