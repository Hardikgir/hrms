@extends('layouts.adminlte')
@section('title', 'Shifts')
@section('page_title', 'Shifts')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Shifts</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Shifts</h3>
        <div class="card-tools">
            @can('create', \App\Modules\Shift\Models\Shift::class)
            <a href="{{ route('shifts.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Shift</a>
            @endcan
            <a href="{{ route('roster.index') }}" class="btn btn-secondary btn-sm">Weekly Roster</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>@endif
        <table class="table table-bordered table-striped">
            <thead><tr><th>Name</th><th>Start</th><th>End</th><th>Break (min)</th><th>Working Hrs</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($shifts as $s)
                <tr>
                    <td>{{ $s->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</td>
                    <td>{{ $s->break_duration }}</td>
                    <td>{{ $s->working_hours }}</td>
                    <td>{{ $s->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        @can('update', $s)
                        <a href="{{ route('shifts.edit', $s) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $s)
                        <form action="{{ route('shifts.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No shifts.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $shifts->links() }}
    </div>
</div>
@endsection
