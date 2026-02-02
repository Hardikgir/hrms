@extends('layouts.ess')
@section('title', 'My Roster')
@section('page_title', 'My Shift Roster')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Roster</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Roster – Week of {{ $weekStart->format('d M Y') }}</h3>
        <div class="card-tools">
            <a href="{{ route('ess.roster', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}" class="btn btn-sm btn-secondary">Prev</a>
            <a href="{{ route('ess.roster', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}" class="btn btn-sm btn-secondary">Next</a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($roster->isNotEmpty())
        <table class="table table-striped mb-0">
            <thead><tr><th>Date</th><th>Shift</th><th>Time</th><th>Notes</th></tr></thead>
            <tbody>
                @foreach($roster as $r)
                <tr>
                    <td>{{ $r->assignment_date->format('D d M Y') }}</td>
                    <td>{{ $r->shift->name ?? '-' }}</td>
                    <td>{{ $r->shift ? \Carbon\Carbon::parse($r->shift->start_time)->format('H:i') . ' – ' . \Carbon\Carbon::parse($r->shift->end_time)->format('H:i') : '-' }}</td>
                    <td>{{ $r->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="p-4 text-center text-muted">No shift assignments for this week.</div>
        @endif
    </div>
</div>
@endsection
