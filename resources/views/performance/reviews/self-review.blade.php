@extends('layouts.adminlte')

@section('title', 'Self Review')
@section('page_title', 'Self Review')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.reviews.index') }}">Reviews</a></li>
    <li class="breadcrumb-item active">Self Review – {{ $review->cycle->name ?? '' }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Self Review – {{ $review->cycle->name ?? '' }}</h3></div>
    <div class="card-body">
        <p class="text-muted">Rate yourself (1–5) and add comments. Your manager will then complete their review.</p>
        <form action="{{ route('performance.reviews.self-review.submit', $review) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="self_rating">Overall self rating (1–5)</label>
                <select class="form-control @error('self_rating') is-invalid @enderror" id="self_rating" name="self_rating">
                    <option value="">— Select —</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('self_rating', $review->self_rating) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('self_rating')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="self_comments">Self comments</label>
                <textarea class="form-control @error('self_comments') is-invalid @enderror" id="self_comments" name="self_comments" rows="4" maxlength="5000">{{ old('self_comments', $review->self_comments) }}</textarea>
                @error('self_comments')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <h5 class="mt-4">Goal ratings (1–5)</h5>
            @foreach($review->goalRatings as $gr)
                <div class="form-group">
                    <label for="goal_scores_{{ $gr->goal_id }}">{{ $gr->goal->title ?? 'Goal' }} <span class="badge badge-{{ $gr->goal->type === 'kra' ? 'primary' : 'info' }}">{{ strtoupper($gr->goal->type ?? '') }}</span></label>
                    <select class="form-control" id="goal_scores_{{ $gr->goal_id }}" name="goal_scores[{{ $gr->goal_id }}]">
                        <option value="">— Select —</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old("goal_scores.{$gr->goal_id}", $gr->self_score) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endforeach
            @if($review->goalRatings->isEmpty())
                <p class="text-muted">No goals linked to this review. Your manager or HR can add goals for this cycle.</p>
            @endif
            <button type="submit" class="btn btn-primary">Submit Self Review</button>
            <a href="{{ auth()->user()->employee ? route('ess.reviews') : route('performance.reviews.show', $review) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
