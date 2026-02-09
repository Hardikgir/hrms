@extends('layouts.adminlte')

@section('title', 'Employment Statuses')
@section('page_title', 'Employment Statuses')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Employment Statuses</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Employment Statuses</h3>
        <div class="card-tools">
            <a href="{{ route('employment-statuses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Status</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Statuses listed here are available when creating or editing employees.</p>
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
                @forelse($employmentStatuses as $status)
                <tr>
                    <td>{{ $status->id }}</td>
                    <td>{{ $status->name }}</td>
                    <td><code>{{ $status->slug }}</code></td>
                    <td>{{ $status->description ?? '-' }}</td>
                    <td>{{ $status->sort_order }}</td>
                    <td>
                        @if($status->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('employment-statuses.edit', $status) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('employment-statuses.destroy', $status) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this employment status?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No employment statuses. <a href="{{ route('employment-statuses.create') }}">Add one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $employmentStatuses->links() }}</div>
    </div>
</div>
@endsection
