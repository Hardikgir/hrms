@extends('layouts.adminlte')

@section('title', 'Review Cycles')
@section('page_title', 'Review Cycles')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Review Cycles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Performance Review Cycles</h3>
        <div class="card-tools">
            @can('manage performance')
            <a href="{{ route('performance.cycles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Cycle
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        <form method="GET" action="{{ route('performance.cycles.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">All statuses</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Reviews</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cycles as $cycle)
                <tr>
                    <td>{{ $cycle->name }}</td>
                    <td>{{ $cycle->period_start->format('d M Y') }} – {{ $cycle->period_end->format('d M Y') }}</td>
                    <td>
                        @if($cycle->status === 'draft')
                            <span class="badge badge-secondary">Draft</span>
                        @elseif($cycle->status === 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-dark">Closed</span>
                        @endif
                    </td>
                    <td>{{ $cycle->reviews()->count() }}</td>
                    <td>
                        <a href="{{ route('performance.cycles.show', $cycle) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @can('update', $cycle)
                        <a href="{{ route('performance.cycles.edit', $cycle) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $cycle)
                        <form action="{{ route('performance.cycles.destroy', $cycle) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this cycle?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No cycles found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $cycles->links() }}</div>
    </div>
</div>
@endsection
