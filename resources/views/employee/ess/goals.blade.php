@extends('layouts.ess')

@section('title', 'My Goals')
@section('page_title', 'My Goals (KRA/OKR)')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Goals</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Goals (KRA / OKR)</h3>
    </div>
    <div class="card-body p-0">
        @if($goals->total() > 0)
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Target</th>
                        <th>Weight</th>
                        <th>Status</th>
                        <th>Cycle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goals as $goal)
                    <tr>
                        <td><span class="badge badge-{{ $goal->type === 'kra' ? 'primary' : 'info' }}">{{ strtoupper($goal->type) }}</span></td>
                        <td><strong>{{ $goal->title }}</strong>@if($goal->description)<br><small class="text-muted">{{ Str::limit($goal->description, 60) }}</small>@endif</td>
                        <td>{{ $goal->target_value ?? '—' }}</td>
                        <td>{{ $goal->weight }}</td>
                        <td>
                            @if($goal->status === 'active')<span class="badge badge-warning">Active</span>
                            @elseif($goal->status === 'achieved')<span class="badge badge-success">Achieved</span>
                            @elseif($goal->status === 'not_achieved')<span class="badge badge-danger">Not Achieved</span>
                            @else<span class="badge badge-secondary">{{ $goal->status }}</span>
                            @endif
                        </td>
                        <td>{{ $goal->cycle->name ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">{{ $goals->links() }}</div>
        @else
            <div class="p-4 text-center text-muted">
                <p>No goals assigned yet.</p>
                <p class="small">Your manager or HR will add KRA/OKR goals for your review cycles.</p>
            </div>
        @endif
    </div>
</div>
@endsection
