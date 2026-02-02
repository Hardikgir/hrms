@extends('layouts.ess')

@section('title', 'My Reviews')
@section('page_title', 'My Performance Reviews')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reviews</li>
@endsection

@section('content')
@if($reviewsToComplete->isNotEmpty())
<div class="card card-outline card-primary mb-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tasks mr-1"></i> Reviews to complete (as reviewer)</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Cycle</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviewsToComplete as $rev)
                <tr>
                    <td>{{ $rev->employee->full_name ?? '-' }}</td>
                    <td>{{ $rev->cycle->name ?? '-' }}</td>
                    <td>
                        @if($rev->status === 'pending')<span class="badge badge-warning">Pending</span>
                        @elseif($rev->status === 'self_review')<span class="badge badge-info">Self Review</span>
                        @else<span class="badge badge-primary">Manager Review</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('performance.reviews.manager-review', $rev) }}" class="btn btn-sm btn-primary">Complete review</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Performance Reviews</h3>
    </div>
    <div class="card-body p-0">
        @if($myReviews->isNotEmpty())
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Cycle</th>
                        <th>Reviewer</th>
                        <th>Status</th>
                        <th>Overall</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myReviews as $review)
                    <tr>
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
                            <a href="{{ route('performance.reviews.show', $review) }}" class="btn btn-sm btn-info">View</a>
                            @if($review->canSubmitSelfReview())
                                <a href="{{ route('performance.reviews.self-review', $review) }}" class="btn btn-sm btn-warning">Self Review</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-4 text-center text-muted">
                <p>No performance reviews yet.</p>
                <p class="small">Your manager or HR will create a review for you when a cycle is active.</p>
            </div>
        @endif
    </div>
</div>
@endsection
