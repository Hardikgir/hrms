@extends('layouts.adminlte')
@section('title', __('messages.training_courses'))
@section('page_title', __('messages.training_courses'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.training_courses') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.training_courses') }}</h3>
            <div class="card-tools">
                @can('create', \App\Modules\Training\Models\TrainingCourse::class)
                    <a href="{{ route('training.courses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                        {{ __('messages.new_course') }}</a>
                @endcan
                <a href="{{ route('training.assignments.index') }}"
                    class="btn btn-secondary btn-sm">{{ __('messages.assignments') }}</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.type') }}</th>
                        <th>{{ __('messages.duration') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->type ?? '-' }}</td>
                            <td>{{ $c->duration_hours ?? 0 }} {{ __('messages.hrs') }}</td>
                            <td>{{ $c->is_active ? __('messages.active') : __('messages.inactive') }}</td>
                            <td class="action-buttons">
                                @can('update', $c)
                                    <a href="{{ route('training.courses.edit', $c) }}" class="btn btn-sm btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('messages.no_courses') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $courses->links() }}
        </div>
    </div>
@endsection