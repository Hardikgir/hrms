@extends('layouts.ess')

@section('title', __('messages.my_tasks'))
@section('page_title', __('messages.my_tasks'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.my_tasks') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.task_list') }}</h3>
                </div>
                <div class="card-body p-0">
                    @if(count($tasks) > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">{{ __('messages.tasks') }}</th>
                                    <th width="35%">{{ __('messages.description') }}</th>
                                    <th width="12%">{{ __('messages.due_date') }}</th>
                                    <th width="10%">{{ __('messages.status') }}</th>
                                    <th width="13%">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{ $task->id }}</td>
                                        <td>
                                            <strong>{{ $task->title }}</strong>
                                            @if($task->priority === 'high')
                                                <span class="badge badge-danger ml-2">{{ __('messages.high') }}</span>
                                            @elseif($task->priority === 'medium')
                                                <span class="badge badge-warning ml-2">{{ __('messages.medium') }}</span>
                                            @else
                                                <span class="badge badge-info ml-2">{{ __('messages.low') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $task->description }}</td>
                                        <td>{{ $task->due_date->format('d M Y') }}</td>
                                        <td>
                                            @if($task->status === 'completed')
                                                <span class="badge badge-success">{{ __('messages.completed') }}</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="badge badge-info">{{ __('messages.in_progress') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->status !== 'completed')
                                                <form action="{{ route('ess.tasks.complete', $task) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-success">{{ __('messages.mark_completed') }}</button>
                                                </form>
                                            @endif
                                            @if($task->action_route)
                                                @php
                                                    $actionUrl = Str::startsWith($task->action_route, ['http://', 'https://']) ? $task->action_route : (Route::has($task->action_route) ? route($task->action_route) : url($task->action_route));
                                                @endphp
                                                <a href="{{ $actionUrl }}"
                                                    class="btn btn-sm btn-primary">{{ $task->action_label ?? __('messages.submit') }}</a>
                                            @else
                                                @if($task->status === 'completed')
                                                    <span class="text-muted">—</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-3">
                            <p class="text-muted">{{ __('messages.no_tasks_assigned') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection