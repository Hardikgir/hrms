@extends('layouts.adminlte')

@section('title', 'Manager Review')
@section('page_title', 'Manager Review')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.reviews.index') }}">Reviews</a></li>
    <li class="breadcrumb-item active">Manager Review – {{ $review->employee->full_name ?? '' }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Manager Review – {{ $review->employee->full_name ?? '' }} ({{ $review->cycle->name ?? '' }})</h3></div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <p class="text-muted">Complete your review and rate each goal (1–5). Overall rating is calculated from goal weights.</p>
        <form action="{{ route('performance.reviews.manager-review.submit', $review) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="manager_rating">Overall manager rating (1–5)</label>
                <select class="form-control @error('manager_rating') is-invalid @enderror" id="manager_rating" name="manager_rating">
                    <option value="">— Select —</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('manager_rating', $review->manager_rating) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('manager_rating')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="manager_comments">Manager comments</label>
                <textarea class="form-control @error('manager_comments') is-invalid @enderror" id="manager_comments" name="manager_comments" rows="4" maxlength="5000">{{ old('manager_comments', $review->manager_comments) }}</textarea>
                @error('manager_comments')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <h5 class="mt-4">Goal ratings (1–5)</h5>
            @foreach($review->goalRatings as $gr)
                <div class="form-group">
                    <label for="goal_scores_{{ $gr->goal_id }}">{{ $gr->goal->title ?? 'Goal' }} <span class="badge badge-{{ $gr->goal->type === 'kra' ? 'primary' : 'info' }}">{{ strtoupper($gr->goal->type ?? '') }}</span> (weight: {{ $gr->goal->weight ?? 0 }})</label>
                    <select class="form-control" id="goal_scores_{{ $gr->goal_id }}" name="goal_scores[{{ $gr->goal_id }}]">
                        <option value="">— Select —</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old("goal_scores.{$gr->goal_id}", $gr->manager_score) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endforeach
            @if($review->goalRatings->isEmpty())
                <p class="text-muted">No goals linked. You can still submit the overall rating and comments.</p>
            @endif
            <button type="submit" class="btn btn-primary">Submit Manager Review</button>
            <a href="{{ route('performance.reviews.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
