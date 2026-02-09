@extends('layouts.adminlte')

@section('title', 'Locations')
@section('page_title', 'Locations')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Locations</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Locations</h3>
        <div class="card-tools">
            <a href="{{ route('locations.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Location</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Locations listed here appear in the employee job details dropdown.</p>
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
                    <th>City / Country</th>
                    <th>Employees</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $loc)
                <tr>
                    <td>{{ $loc->id }}</td>
                    <td>{{ $loc->name }}</td>
                    <td><code>{{ $loc->code ?? '-' }}</code></td>
                    <td>{{ $loc->city ?? '-' }} / {{ $loc->country ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $loc->employees_count }}</span></td>
                    <td>
                        @if($loc->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('locations.edit', $loc) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('locations.destroy', $loc) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this location?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No locations. <a href="{{ route('locations.create') }}">Add one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $locations->links() }}</div>
    </div>
</div>
@endsection
