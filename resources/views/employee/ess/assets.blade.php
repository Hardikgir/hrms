@extends('layouts.ess')
@section('title', 'My Assets')
@section('page_title', 'My Assets')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Assets</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Assets Assigned to Me</h3></div>
    <div class="card-body p-0">
        @if($assets->total() > 0)
        <table class="table table-striped mb-0">
            <thead><tr><th>Name</th><th>Type</th><th>Serial / Tag</th><th>Status</th><th>Return</th></tr></thead>
            <tbody>
                @foreach($assets as $a)
                @php $pending = $a->pendingReturnRequest(); $latest = $a->latestReturnRequest(); @endphp
                <tr>
                    <td>{{ $a->name }}</td>
                    <td>{{ $a->assetType?->name ?? $a->type }}</td>
                    <td>{{ $a->serial_number ?? $a->asset_tag ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$a->status)) }}</td>
                    <td class="action-buttons">
                        @if($pending)
                            <span class="badge badge-warning">Return pending</span>
                        @elseif($latest && $latest->isDeclined())
                            <div class="small">
                                <span class="badge badge-danger">Return declined</span>
                                @if($latest->admin_note)
                                    <div class="mt-1 text-muted">Note: {{ $latest->admin_note }}</div>
                                @endif
                                <form action="{{ route('ess.assets.request-return', $a) }}" method="POST" class="mt-1 d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Request return again</button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('ess.assets.request-return', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Submit return request for this asset?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Return asset</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $assets->links() }}</div>
        @else
        <div class="p-4 text-center text-muted">No assets assigned to you.</div>
        @endif
    </div>
</div>
@endsection
