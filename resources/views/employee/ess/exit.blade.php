@extends('layouts.ess')
@section('title', 'My Exit')
@section('page_title', 'My Exit / Resignation')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Exit</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Exit Requests</h3>
        <div class="card-tools">
            <a href="{{ route('ess.exit.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Submit Resignation</a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($exits->total() > 0)
        <table class="table table-striped mb-0">
            <thead><tr><th>Resignation Date</th><th>Last Working</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($exits as $e)
                <tr>
                    <td>{{ $e->resignation_date->format('d M Y') }}</td>
                    <td>{{ $e->last_working_date->format('d M Y') }}</td>
                    <td>{{ $e->reason ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$e->status)) }}</td>
                    <td><a href="{{ route('exit.show', $e) }}" class="btn btn-sm btn-info">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $exits->links() }}</div>
        @else
        <div class="p-4 text-center text-muted">No exit requests. <a href="{{ route('ess.exit.create') }}">Submit resignation</a>.</div>
        @endif
    </div>
</div>
@endsection
