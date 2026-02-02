@extends('layouts.adminlte')

@section('title', 'Performance Review')
@section('page_title', 'Performance Review')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.reviews.index') }}">Reviews</a></li>
    <li class="breadcrumb-item active">{{ $review->employee->full_name ?? 'Review' }} – {{ $review->cycle->name ?? '' }}</li>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Review Details</h3>
                <div class="card-tools">
                    @if($review->canSubmitSelfReview() && auth()->user()->employee && auth()->user()->employee->id === $review->employee_id)
                        <a href="{{ route('performance.reviews.self-review', $review) }}" class="btn btn-sm btn-warning">Submit Self Review</a>
                    @endif
                    @if($review->canSubmitManagerReview() && $review->reviewer_id === auth()->id())
                        <a href="{{ route('performance.reviews.manager-review', $review) }}" class="btn btn-sm btn-primary">Submit Manager Review</a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <p><strong>Employee:</strong> {{ $review->employee->full_name ?? '-' }}</p>
                <p><strong>Cycle:</strong> {{ $review->cycle->name ?? '-' }}</p>
                <p><strong>Reviewer:</strong> {{ $review->reviewer->name ?? '-' }}</p>
                <p><strong>Status:</strong>
                    @if($review->status === 'pending')<span class="badge badge-warning">Pending</span>
                    @elseif($review->status === 'self_review')<span class="badge badge-info">Self Review</span>
                    @elseif($review->status === 'manager_review')<span class="badge badge-primary">Manager Review</span>
                    @else<span class="badge badge-success">Completed</span>
                    @endif
                </p>
                @if($review->self_rating)
                    <p><strong>Self rating:</strong> {{ $review->self_rating }}/5</p>
                @endif
                @if($review->manager_rating)
                    <p><strong>Manager rating:</strong> {{ $review->manager_rating }}/5</p>
                @endif
                @if($review->overall_rating)
                    <p><strong>Overall rating:</strong> {{ $review->overall_rating }}/5</p>
                @endif
                @if($review->self_comments)
                    <p><strong>Self comments:</strong><br>{{ $review->self_comments }}</p>
                @endif
                @if($review->manager_comments)
                    <p><strong>Manager comments:</strong><br>{{ $review->manager_comments }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header"><h3 class="card-title">Goal Ratings</h3></div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Goal (KRA/OKR)</th>
                    <th>Self score</th>
                    <th>Manager score</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($review->goalRatings as $gr)
                <tr>
                    <td>
                        <strong>{{ $gr->goal->title ?? '-' }}</strong>
                        <span class="badge badge-{{ $gr->goal->type === 'kra' ? 'primary' : 'info' }} ml-1">{{ strtoupper($gr->goal->type ?? '') }}</span>
                    </td>
                    <td>{{ $gr->self_score !== null ? $gr->self_score . '/5' : '—' }}</td>
                    <td>{{ $gr->manager_score !== null ? $gr->manager_score . '/5' : '—' }}</td>
                    <td>{{ Str::limit($gr->comment, 60) ?: '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">No goal ratings yet. Goals are linked when you open Self Review or Manager Review.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('performance.reviews.index') }}" class="btn btn-secondary">Back to list</a>
</div>
@endsection
