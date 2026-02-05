@extends('layouts.adminlte')
@section('title', 'Asset History')
@section('page_title', 'Asset Assignment History')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Assets</a></li>
    <li class="breadcrumb-item active">{{ $asset->name }} – History</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $asset->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Assets</a>
            @can('update', $asset)
            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">
            <strong>Type:</strong> {{ $asset->assetType?->name ?? $asset->type }}
            @if($asset->serial_number || $asset->asset_tag)
                &nbsp;|&nbsp; <strong>Serial / Tag:</strong> {{ $asset->serial_number ?? $asset->asset_tag ?? '-' }}
            @endif
        </p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Assigned To</th>
                    <th>Assigned At</th>
                    <th>Assigned By</th>
                    <th>Returned At</th>
                    <th>Returned By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asset->assignmentHistories as $h)
                <tr>
                    <td>{{ $h->employee?->full_name ?? '—' }}</td>
                    <td>{{ $h->assigned_at?->format('M j, Y g:i A') ?? '—' }}</td>
                    <td>{{ $h->assignedByUser?->name ?? '—' }}</td>
                    <td>{{ $h->returned_at?->format('M j, Y g:i A') ?? '—' }}</td>
                    <td>{{ $h->returnedByUser?->name ?? '—' }}</td>
                    <td>
                        @if($h->isCurrent())
                            <span class="badge badge-success">Current</span>
                        @else
                            <span class="badge badge-secondary">Returned</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No assignment history yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
