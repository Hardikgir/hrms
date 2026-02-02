@extends('layouts.adminlte')
@section('title', 'Weekly Roster')
@section('page_title', 'Weekly Roster')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shifts.index') }}">Shifts</a></li>
    <li class="breadcrumb-item active">Roster</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Weekly Roster – {{ $dateFrom->format('d M') }} – {{ $dateTo->format('d M Y') }}</h3>
        <div class="card-tools">
            <a href="{{ route('roster.index', ['week' => $dateFrom->copy()->subWeek()->toDateString()]) }}" class="btn btn-sm btn-secondary">Prev Week</a>
            <a href="{{ route('roster.index', ['week' => $dateFrom->copy()->addWeek()->toDateString()]) }}" class="btn btn-sm btn-secondary">Next Week</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>@endif
        @can('create', \App\Modules\Shift\Models\ShiftAssignment::class)
        <form action="{{ route('roster.store') }}" method="POST" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-3"><select name="employee_id" class="form-control" required>@foreach($employees as $emp)<option value="{{ $emp->id }}">{{ $emp->full_name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="shift_id" class="form-control" required>@foreach($shifts as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><input type="date" name="assignment_date" class="form-control" value="{{ $dateFrom->toDateString() }}" required></div>
                <div class="col-md-2"><input type="text" name="notes" class="form-control" placeholder="Notes"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Assign</button></div>
            </div>
        </form>
        @endcan
        <table class="table table-bordered table-striped table-sm">
            <thead><tr><th>Employee</th><th>Date</th><th>Shift</th><th>Notes</th>@can('delete', \App\Modules\Shift\Models\ShiftAssignment::class)<th>Actions</th>@endcan</tr></thead>
            <tbody>
                @forelse($roster as $r)
                <tr>
                    <td>{{ $r->employee->full_name ?? '-' }}</td>
                    <td>{{ $r->assignment_date->format('d M Y') }}</td>
                    <td>{{ $r->shift->name ?? '-' }}</td>
                    <td>{{ $r->notes ?? '-' }}</td>
                    @can('delete', $r)
                    <td>
                        <form action="{{ route('roster.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Remove</button></form>
                    </td>
                    @endcan
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No assignments for this week.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
