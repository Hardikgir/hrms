@extends('layouts.ess')

@section('title', 'Training Session')
@section('page_title', 'Training Session')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.tasks') }}">Tasks</a></li>
    <li class="breadcrumb-item active">Training Session</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $training['title'] }}</h3>
                <div class="card-tools">
                    <a href="{{ route('ess.tasks') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Back to Tasks</a>
                </div>
            </div>
            <div class="card-body">
                <p class="lead">{{ $training['description'] }}</p>

                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <th width="25%">Scheduled</th>
                            <td>{{ \Carbon\Carbon::parse($training['scheduled_at'])->format('l, d M Y \a\t h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>{{ $training['duration'] }}</td>
                        </tr>
                        <tr>
                            <th>Mode</th>
                            <td>{{ $training['mode'] }}</td>
                        </tr>
                        <tr>
                            <th>Venue</th>
                            <td>{{ $training['venue'] }}</td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td>{{ $training['contact'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <h5 class="mb-2">Agenda</h5>
                <ul>
                    @foreach($training['agenda'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>

                <hr class="my-4">

                <p class="text-muted mb-2">After attending the session, confirm your attendance below.</p>
                <form action="{{ route('ess.training-session.confirm') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> I've attended – confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
