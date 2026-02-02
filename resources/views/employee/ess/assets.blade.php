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
            <thead><tr><th>Name</th><th>Type</th><th>Serial / Tag</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($assets as $a)
                <tr>
                    <td>{{ $a->name }}</td>
                    <td>{{ $a->type }}</td>
                    <td>{{ $a->serial_number ?? $a->asset_tag ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$a->status)) }}</td>
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
