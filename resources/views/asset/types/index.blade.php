@extends('layouts.adminlte')

@section('title', 'Asset Types')
@section('page_title', 'Asset Types')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Assets</a></li>
    <li class="breadcrumb-item active">Types</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Asset Types</h3>
        <div class="card-tools">
            <a href="{{ route('asset-types.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Type</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Types listed here are available when creating or editing assets.</p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assetTypes as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>{{ $type->name }}</td>
                    <td><code>{{ $type->slug }}</code></td>
                    <td>{{ $type->sort_order }}</td>
                    <td>
                        @if($type->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('asset-types.edit', $type) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('asset-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this asset type?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No asset types. <a href="{{ route('asset-types.create') }}">Add one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $assetTypes->links() }}</div>
    </div>
</div>
@endsection
