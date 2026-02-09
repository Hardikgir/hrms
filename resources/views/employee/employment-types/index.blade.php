@extends('layouts.adminlte')

@section('title', 'Employment Types')
@section('page_title', 'Employment Types')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Employment Types</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Employment Types</h3>
        <div class="card-tools">
            <a href="{{ route('employment-types.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Type</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Types listed here are available when creating or editing employees.</p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employmentTypes as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>{{ $type->name }}</td>
                    <td><code>{{ $type->slug }}</code></td>
                    <td>{{ $type->description ?? '-' }}</td>
                    <td>{{ $type->sort_order }}</td>
                    <td>
                        @if($type->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('employment-types.edit', $type) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('employment-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this employment type?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No employment types. <a href="{{ route('employment-types.create') }}">Add one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $employmentTypes->links() }}</div>
    </div>
</div>
@endsection
