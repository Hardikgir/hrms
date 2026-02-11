@extends('layouts.ess')

@section('title', __('messages.training_session'))
@section('page_title', __('messages.training_session'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.tasks') }}">{{ __('messages.tasks') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.training_session') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $training['title'] }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('ess.tasks') }}" class="btn btn-sm btn-secondary"><i
                                class="fas fa-arrow-left"></i> {{ __('messages.back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $training['description'] }}</p>

                    <table class="table table-bordered mb-4">
                        <tbody>
                            <tr>
                                <th width="25%">{{ __('messages.scheduled') }}</th>
                                <td>{{ \Carbon\Carbon::parse($training['scheduled_at'])->format('l, d M Y \a\t h:i A') }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.duration') }}</th>
                                <td>{{ $training['duration'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.type') }}</th>
                                <td>{{ $training['mode'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.venue') }}</th>
                                <td>{{ $training['venue'] }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.contact') }}</th>
                                <td>{{ $training['contact'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="mb-2">{{ __('messages.agenda') }}</h5>
                    <ul>
                        @foreach($training['agenda'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                    <hr class="my-4">

                    @if(!$trainingTask)
                        <p class="text-muted mb-0">{{ __('messages.no_pending_training') }}</p>
                    @elseif($trainingTask->status === 'completed')
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> {{ __('messages.training_attendance_submitted') }}
                        </div>
                    @else
                        <p class="text-muted mb-2">{{ __('messages.confirm_attendance_hint') }}</p>
                        <form action="{{ route('ess.training-session.confirm') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                {{ __('messages.confirm_attendance_btn') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection