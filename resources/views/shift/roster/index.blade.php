@extends('layouts.adminlte')
@section('title', __('messages.weekly_roster'))
@section('page_title', __('messages.weekly_roster'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shifts.index') }}">{{ __('messages.shifts') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.weekly_roster') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.weekly_roster') }} – {{ $dateFrom->format('d M') }} –
                {{ $dateTo->format('d M Y') }}</h3>
            <div class="card-tools">
                <a href="{{ route('roster.index', ['week' => $dateFrom->copy()->subWeek()->toDateString()]) }}"
                    class="btn btn-sm btn-secondary">{{ __('messages.prev_week') }}</a>
                <a href="{{ route('roster.index', ['week' => $dateFrom->copy()->addWeek()->toDateString()]) }}"
                    class="btn btn-sm btn-secondary">{{ __('messages.next_week') }}</a>
            </div>
        </div>
        <div class="card-body">
            @can('create', \App\Modules\Shift\Models\ShiftAssignment::class)
                <form action="{{ route('roster.store') }}" method="POST" class="mb-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-3"><select name="employee_id" class="form-control"
                                required>@foreach($employees as $emp)<option value="{{ $emp->id }}">{{ $emp->full_name }}
                                </option>@endforeach</select></div>
                        <div class="col-md-2"><select name="shift_id" class="form-control" required>@foreach($shifts as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
                        <div class="col-md-2"><input type="date" name="assignment_date" class="form-control"
                                value="{{ $dateFrom->toDateString() }}" required></div>
                        <div class="col-md-2"><input type="text" name="notes" class="form-control"
                                placeholder="{{ __('messages.notes') }}"></div>
                        <div class="col-md-2"><button type="submit" class="btn btn-primary">{{ __('messages.assign') }}</button>
                        </div>
                    </div>
                </form>
            @endcan
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.shifts') }}</th>
                        <th>{{ __('messages.notes') }}</th>@can('delete', \App\Modules\Shift\Models\ShiftAssignment::class)
                        <th>{{ __('messages.actions') }}</th>@endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($roster as $r)
                        <tr>
                            <td>{{ $r->employee->full_name ?? '-' }}</td>
                            <td>{{ $r->assignment_date->format('d M Y') }}</td>
                            <td>{{ $r->shift->name ?? '-' }}</td>
                            <td>{{ $r->notes ?? '-' }}</td>
                            @can('delete', $r)
                                <td class="action-buttons">
                                    <form action="{{ route('roster.destroy', $r) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('{{ __('messages.remove_confirm') }}');">@csrf
                                        @method('DELETE')<button type="submit"
                                            class="btn btn-sm btn-danger">{{ __('messages.remove') }}</button></form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('messages.no_assignments') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection