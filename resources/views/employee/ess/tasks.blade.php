@extends('layouts.ess')

@section('title', 'My Tasks')
@section('page_title', 'My Tasks')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Tasks</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Tasks</h3>
            </div>
            <div class="card-body p-0">
                @if(count($tasks) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Task</th>
                                <th width="35%">Description</th>
                                <th width="12%">Due Date</th>
                                <th width="10%">Status</th>
                                <th width="13%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->id }}</td>
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->priority === 'high')
                                            <span class="badge badge-danger ml-2">High</span>
                                        @elseif($task->priority === 'medium')
                                            <span class="badge badge-warning ml-2">Medium</span>
                                        @else
                                            <span class="badge badge-info ml-2">Low</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->due_date->format('d M Y') }}</td>
                                    <td>
                                        @if($task->status === 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="badge badge-info">In Progress</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->action_route)
                                            @php
                                                $actionUrl = str_starts_with($task->action_route, 'http') || str_starts_with($task->action_route, '/') ? $task->action_route : route($task->action_route);
                                            @endphp
                                            <a href="{{ $actionUrl }}" class="btn btn-sm btn-primary">{{ $task->action_label ?? 'Submit' }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3">
                        <p class="text-muted">No tasks assigned.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

