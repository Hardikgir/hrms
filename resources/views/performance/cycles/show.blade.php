@extends('layouts.adminlte')

@section('title', $cycle->name)
@section('page_title', $cycle->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.index') }}">Review Cycles</a></li>
    <li class="breadcrumb-item active">{{ $cycle->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cycle Details</h3>
                <div class="card-tools">
                    @can('update', $cycle)
                    <a href="{{ route('performance.cycles.edit', $cycle) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <p><strong>{{ __('messages.period_label') }}:</strong> {{ $cycle->period_start->format('d M Y') }} – {{ $cycle->period_end->format('d M Y') }}</p>
                <p><strong>Status:</strong>
                    @if($cycle->status === 'draft')<span class="badge badge-secondary">Draft</span>
                    @elseif($cycle->status === 'active')<span class="badge badge-success">Active</span>
                    @else<span class="badge badge-dark">Closed</span>
                    @endif
                </p>
                @can('manage performance')
                <a href="{{ route('performance.reviews.create') }}?cycle_id={{ $cycle->id }}" class="btn btn-success btn-sm mt-2">
                    <i class="fas fa-plus"></i> Add Review
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header"><h3 class="card-title">Reviews in this cycle</h3></div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Reviewer</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cycle->reviews as $review)
                <tr>
                    <td>{{ $review->employee->full_name ?? '-' }}</td>
                    <td>{{ $review->reviewer->name ?? '-' }}</td>
                    <td>
                        @if($review->status === 'pending')<span class="badge badge-warning">Pending</span>
                        @elseif($review->status === 'self_review')<span class="badge badge-info">Self Review</span>
                        @elseif($review->status === 'manager_review')<span class="badge badge-primary">Manager Review</span>
                        @else<span class="badge badge-success">Completed</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('performance.reviews.show', $review) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">No reviews in this cycle.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
