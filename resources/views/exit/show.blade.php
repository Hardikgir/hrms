@extends('layouts.adminlte')
@section('title', __('messages.exit_requests'))
@section('page_title', __('messages.exit_requests'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('exit.index') }}">{{ __('messages.exit') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.view') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.exit_requests') }} – {{ $exit->employee->full_name ?? '-' }}</h3>
        </div>
        <div class="card-body">
            <p><strong>{{ __('messages.employee') }}:</strong> {{ $exit->employee->full_name ?? '-' }}</p>
            <p><strong>{{ __('messages.resignation_date') }}:</strong> {{ $exit->resignation_date->format('d M Y') }}</p>
            <p><strong>{{ __('messages.last_working_day') }}:</strong> {{ $exit->last_working_date->format('d M Y') }}</p>
            <p><strong>{{ __('messages.reason') }}:</strong> {{ $exit->reason ?? '-' }}</p>
            <p><strong>{{ __('messages.reason_details') }}:</strong> {{ $exit->reason_details ?? '-' }}</p>
            <p><strong>{{ __('messages.status') }}:</strong> {{ __('messages.' . $exit->status) }}</p>
            @if($exit->exit_interview_notes)
            <p><strong>{{ __('messages.exit_interview_notes') }}:</strong> {{ $exit->exit_interview_notes }}</p>@endif
            @if($exit->settlement_amount !== null)
                <p><strong>{{ __('messages.settlement_amount') }}:</strong> {{ number_format($exit->settlement_amount, 2) }}</p>
            @endif
            @if($exit->checklist)
                <p><strong>{{ __('messages.checklist') }}:</strong></p>
                <ul>@foreach($exit->checklist as $k => $v)<li>{{ __('messages.' . $k) }}:
                {{ $v ? __('messages.done') : __('messages.pending') }}</li>@endforeach</ul>
            @endif
        </div>
    </div>
    @can('update', $exit)
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ __('messages.update_status_checklist') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('exit.status', $exit) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="form-group"><label for="status">{{ __('messages.status') }}</label><select class="form-control"
                            id="status" name="status">
                            <option value="pending" {{ $exit->status == 'pending' ? 'selected' : '' }}>
                                {{ __('messages.pending') }}</option>
                            <option value="clearance" {{ $exit->status == 'clearance' ? 'selected' : '' }}>
                                {{ __('messages.clearance') }}</option>
                            <option value="exit_interview" {{ $exit->status == 'exit_interview' ? 'selected' : '' }}>
                                {{ __('messages.exit_interview') }}</option>
                            <option value="settlement" {{ $exit->status == 'settlement' ? 'selected' : '' }}>
                                {{ __('messages.settlement') }}</option>
                            <option value="completed" {{ $exit->status == 'completed' ? 'selected' : '' }}>
                                {{ __('messages.completed') }}</option>
                        </select></div>
                    <div class="form-group"><label
                            for="exit_interview_notes">{{ __('messages.exit_interview_notes') }}</label><textarea
                            class="form-control" id="exit_interview_notes" name="exit_interview_notes"
                            rows="2">{{ $exit->exit_interview_notes }}</textarea></div>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update_status') }}</button>
                </form>
                <form action="{{ route('exit.checklist', $exit) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="form-group"><label>{{ __('messages.checklist') }}</label>
                        @foreach($exit->checklist ?? ['it' => false, 'hr' => false, 'finance' => false, 'assets' => false] as $k => $v)
                            <div><label><input type="checkbox" name="checklist[{{ $k }}]" value="1" {{ $v ? 'checked' : '' }}>
                                    {{ __('messages.' . $k) }}</label></div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-secondary">{{ __('messages.update_checklist') }}</button>
                </form>
                @if($exit->status !== 'completed')
                    <form action="{{ route('exit.clearance', $exit) }}" method="POST" class="d-inline">@csrf<button type="submit"
                            class="btn btn-info">{{ __('messages.complete_clearance') }}</button></form>
                    <form action="{{ route('exit.settlement', $exit) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('{{ __('messages.confirm_record_settlement') }}');">@csrf<input type="number"
                            step="0.01" name="settlement_amount" placeholder="{{ __('messages.amount') }}" required> <input
                            type="date" name="settlement_paid_at"> <button type="submit"
                            class="btn btn-success">{{ __('messages.record_settlement') }}</button></form>
                @endif
            </div>
        </div>
    @endcan
    <a href="{{ route('exit.index') }}" class="btn btn-secondary mt-2">{{ __('messages.back') }}</a>
@endsection