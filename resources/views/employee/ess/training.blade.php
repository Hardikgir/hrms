@extends('layouts.ess')
@section('title', 'My Training')
@section('page_title', 'My Training')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Training</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">My Training Assignments</h3></div>
    <div class="card-body p-0">
        @if($assignments->isNotEmpty())
        <table class="table table-striped mb-0">
            <thead><tr><th>Course</th><th>Type</th><th>Duration</th><th>Status</th><th>Assigned</th><th>Completed</th></tr></thead>
            <tbody>
                @foreach($assignments as $a)
                <tr>
                    <td>{{ $a->course->name ?? '-' }}</td>
                    <td>{{ $a->course->type ?? '-' }}</td>
                    <td>{{ $a->course->duration_hours ?? 0 }} hrs</td>
                    <td>{{ ucfirst(str_replace('_',' ',$a->status)) }}</td>
                    <td>{{ $a->assigned_at?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $a->completed_at?->format('d M Y') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="p-4 text-center text-muted">No training assignments yet.</div>
        @endif
    </div>
</div>
@endsection
