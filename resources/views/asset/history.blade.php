@extends('layouts.adminlte')
@section('title', __('messages.asset_history'))
@section('page_title', __('messages.asset_assignment_history'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">{{ __('messages.assets') }}</a></li>
    <li class="breadcrumb-item active">{{ $asset->name }} – {{ __('messages.asset_history') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $asset->name }}</h3>
            <div class="card-tools">
                <a href="{{ route('assets.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_assets') }}</a>
                @can('update', $asset)
                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i>
                        {{ __('messages.edit') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <strong>{{ __('messages.type') }}:</strong> {{ $asset->assetType?->name ?? $asset->type }}
                @if($asset->serial_number || $asset->asset_tag)
                    &nbsp;|&nbsp; <strong>{{ __('messages.serial_tag') }}:</strong>
                    {{ $asset->serial_number ?? $asset->asset_tag ?? '-' }}
                @endif
            </p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.assigned_to') }}</th>
                        <th>{{ __('messages.assigned_at') }}</th>
                        <th>{{ __('messages.assigned_by') }}</th>
                        <th>{{ __('messages.returned_at') }}</th>
                        <th>{{ __('messages.returned_by') }}</th>
                        <th>{{ __('messages.status') }}</th>
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
                                    <span class="badge badge-success">{{ __('messages.current') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('messages.returned') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_assignment_history') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection