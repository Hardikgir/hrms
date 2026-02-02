@extends('layouts.adminlte')

@section('title', 'Performance Reviews')
@section('page_title', 'Performance Reviews')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.index') }}">Performance</a></li>
    <li class="breadcrumb-item active">Reviews</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Performance Reviews</h3>
        <div class="card-tools">
            @can('manage performance')
            <a href="{{ route('performance.reviews.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Review</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('performance.reviews.index') }}" class="mb-3">
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
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="self_review" {{ request('status') == 'self_review' ? 'selected' : '' }}>Self Review</option>
                        <option value="manager_review" {{ request('status') == 'manager_review' ? 'selected' : '' }}>Manager Review</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                    <th>Cycle</th>
                    <th>Reviewer</th>
                    <th>Status</th>
                    <th>Overall</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->employee->full_name ?? '-' }}</td>
                    <td>{{ $review->cycle->name ?? '-' }}</td>
                    <td>{{ $review->reviewer->name ?? '-' }}</td>
                    <td>
                        @if($review->status === 'pending')<span class="badge badge-warning">Pending</span>
                        @elseif($review->status === 'self_review')<span class="badge badge-info">Self Review</span>
                        @elseif($review->status === 'manager_review')<span class="badge badge-primary">Manager Review</span>
                        @else<span class="badge badge-success">Completed</span>
                        @endif
                    </td>
                    <td>{{ $review->overall_rating ? $review->overall_rating . '/5' : '—' }}</td>
                    <td>
                        <a href="{{ route('performance.reviews.show', $review) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @if($review->canSubmitSelfReview() && auth()->user()->employee && auth()->user()->employee->id === $review->employee_id)
                            <a href="{{ route('performance.reviews.self-review', $review) }}" class="btn btn-sm btn-warning">Self Review</a>
                        @endif
                        @if($review->canSubmitManagerReview() && $review->reviewer_id === auth()->id())
                            <a href="{{ route('performance.reviews.manager-review', $review) }}" class="btn btn-sm btn-primary">Manager Review</a>
                        @endif
                        @can('delete', $review)
                        <form action="{{ route('performance.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No reviews found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $reviews->links() }}</div>
    </div>
</div>
@endsection
