@extends('layouts.adminlte')
@section('title', 'Exit Request')
@section('page_title', 'Exit Request')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('exit.index') }}">Exit</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Exit Request – {{ $exit->employee->full_name ?? '-' }}</h3></div>
    <div class="card-body">
        <p><strong>Employee:</strong> {{ $exit->employee->full_name ?? '-' }}</p>
        <p><strong>Resignation Date:</strong> {{ $exit->resignation_date->format('d M Y') }}</p>
        <p><strong>Last Working Date:</strong> {{ $exit->last_working_date->format('d M Y') }}</p>
        <p><strong>Reason:</strong> {{ $exit->reason ?? '-' }}</p>
        <p><strong>Reason Details:</strong> {{ $exit->reason_details ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst(str_replace('_',' ',$exit->status)) }}</p>
        @if($exit->exit_interview_notes)<p><strong>Exit Interview Notes:</strong> {{ $exit->exit_interview_notes }}</p>@endif
        @if($exit->settlement_amount !== null)<p><strong>Settlement Amount:</strong> {{ number_format($exit->settlement_amount, 2) }}</p>@endif
        @if($exit->checklist)
        <p><strong>Checklist:</strong></p>
        <ul>@foreach($exit->checklist as $k => $v)<li>{{ ucfirst($k) }}: {{ $v ? 'Done' : 'Pending' }}</li>@endforeach</ul>
        @endif
    </div>
</div>
@can('update', $exit)
<div class="card mt-3">
    <div class="card-header"><h3 class="card-title">Update Status / Checklist</h3></div>
    <div class="card-body">
        <form action="{{ route('exit.status', $exit) }}" method="POST" class="mb-3">
            @csrf
            <div class="form-group"><label for="status">Status</label><select class="form-control" id="status" name="status"><option value="pending" {{ $exit->status == 'pending' ? 'selected' : '' }}>Pending</option><option value="clearance" {{ $exit->status == 'clearance' ? 'selected' : '' }}>Clearance</option><option value="exit_interview" {{ $exit->status == 'exit_interview' ? 'selected' : '' }}>Exit Interview</option><option value="settlement" {{ $exit->status == 'settlement' ? 'selected' : '' }}>Settlement</option><option value="completed" {{ $exit->status == 'completed' ? 'selected' : '' }}>Completed</option></select></div>
            <div class="form-group"><label for="exit_interview_notes">Exit Interview Notes</label><textarea class="form-control" id="exit_interview_notes" name="exit_interview_notes" rows="2">{{ $exit->exit_interview_notes }}</textarea></div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
        <form action="{{ route('exit.checklist', $exit) }}" method="POST" class="mb-3">
            @csrf
            <div class="form-group"><label>Checklist</label>
                @foreach($exit->checklist ?? ['it' => false, 'hr' => false, 'finance' => false, 'assets' => false] as $k => $v)
                <div><label><input type="checkbox" name="checklist[{{ $k }}]" value="1" {{ $v ? 'checked' : '' }}> {{ ucfirst($k) }}</label></div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-secondary">Update Checklist</button>
        </form>
        @if($exit->status !== 'completed')
        <form action="{{ route('exit.clearance', $exit) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-info">Complete Clearance</button></form>
        <form action="{{ route('exit.settlement', $exit) }}" method="POST" class="d-inline" onsubmit="return confirm('Record settlement?');">@csrf<input type="number" step="0.01" name="settlement_amount" placeholder="Amount" required> <input type="date" name="settlement_paid_at"> <button type="submit" class="btn btn-success">Record Settlement</button></form>
        @endif
    </div>
</div>
@endcan
<a href="{{ route('exit.index') }}" class="btn btn-secondary mt-2">Back</a>
@endsection
