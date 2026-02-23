@extends('layouts.ess')
@section('title', __('messages.my_assets'))
@section('page_title', __('messages.my_assets'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.assets') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.assets_assigned_to_me') }}</h3>
        </div>
        <div class="card-body p-0">
            @if($assets->total() > 0)
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th>{{ __('messages.serial_tag') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.return') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $a)
                            @php $pending = $a->pendingReturnRequest();
                            $latest = $a->latestReturnRequest(); @endphp
                            <tr>
                                <td>{{ $a->name }}</td>
                                <td>{{ $a->assetType?->name ?? $a->type }}</td>
                                <td>{{ $a->serial_number ?? $a->asset_tag ?? '-' }}</td>
                                <td>{{ __('messages.' . $a->status) }}</td>
                                <td class="action-buttons">
                                    @if($pending)
                                        <span class="badge badge-warning">{{ __('messages.return_pending') }}</span>
                                    @elseif($latest && $latest->isDeclined())
                                        <div class="small">
                                            <span class="badge badge-danger">{{ __('messages.return_declined') }}</span>
                                            @if($latest->admin_note)
                                                <div class="mt-1 text-muted">{{ __('messages.note') }}: {{ $latest->admin_note }}</div>
                                            @endif
                                            <form action="{{ route('ess.assets.request-return', $a) }}" method="POST"
                                                class="mt-1 d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-primary">{{ __('messages.request_return_again') }}</button>
                                            </form>
                                        </div>
                                    @else
                            <form action="{{ route('ess.assets.request-return', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.submit_return_request_confirm') }}');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">{{ __('messages.return_asset') }}</button>
                            </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3">{{ $assets->links() }}</div>
            @else
                <div class="p-4 text-center text-muted">{{ __('messages.no_assets_assigned') }}</div>
            @endif
        </div>
    </div>
@endsection