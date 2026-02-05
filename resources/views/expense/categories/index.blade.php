@extends('layouts.adminlte')

@section('title', 'Expense Categories')
@section('page_title', 'Expense Categories')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Expense Categories</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Expense Categories</h3>
        <div class="card-tools">
            <a href="{{ route('expense-categories.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Category</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Categories shown here are available to employees when submitting expenses.</p>
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
                @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td>{{ $cat->name }}</td>
                    <td><code>{{ $cat->slug }}</code></td>
                    <td>{{ $cat->sort_order }}</td>
                    <td>
                        @if($cat->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('expense-categories.edit', $cat) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('expense-categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No categories. <a href="{{ route('expense-categories.create') }}">Add one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $categories->links() }}</div>
    </div>
</div>
@endsection
