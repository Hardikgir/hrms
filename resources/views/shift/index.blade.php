@extends('layouts.adminlte')
@section('title', __('messages.shifts'))
@section('page_title', __('messages.shifts'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.shifts') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.shifts') }}</h3>
            <div class="card-tools">
                @can('create', \App\Modules\Shift\Models\Shift::class)
                    <a href="{{ route('shifts.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                        {{ __('messages.new_shift') }}</a>
                @endcan
                <a href="{{ route('roster.index') }}"
                    class="btn btn-secondary btn-sm">{{ __('messages.weekly_roster') }}</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.start_time') }}</th>
                        <th>{{ __('messages.end_time') }}</th>
                        <th>{{ __('messages.break_duration_min') }}</th>
                        <th>{{ __('messages.working_hrs') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $s)
                        <tr>
                            <td>{{ $s->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</td>
                            <td>{{ $s->break_duration }}</td>
                            <td>{{ $s->working_hours }}</td>
                            <td>{{ $s->is_active ? __('messages.active') : __('messages.inactive') }}</td>
                            <td class="action-buttons">
                                @can('update', $s)
                                    <a href="{{ route('shifts.edit', $s) }}" class="btn btn-sm btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                @endcan
                                @can('delete', $s)
                                    <form action="{{ route('shifts.destroy', $s) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('{{ __('messages.delete_confirm') }}');">@csrf
                                        @method('DELETE')<button type="submit" class="btn btn-sm btn-danger"><i
                                                class="fas fa-trash"></i></button></form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('messages.no_shifts') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $shifts->links() }}
        </div>
    </div>
@endsection