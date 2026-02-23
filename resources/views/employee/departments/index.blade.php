@extends('layouts.adminlte')

@section('title', __('messages.departments'))
@section('page_title', __('messages.departments'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.departments') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.departments') }}</h3>
            <div class="card-tools">
                <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    {{ __('messages.add_department') }}</a>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small">{{ __('messages.departments_list_help') }}</p>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.code') }}</th>
                        <th>{{ __('messages.parent_department') }}</th>
                        <th>{{ __('messages.managers') }}</th>
                        <th>{{ __('messages.employees_count') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>{{ $dept->id }}</td>
                            <td>{{ $dept->name }}</td>
                            <td><code>{{ $dept->code ?? '-' }}</code></td>
                            <td>{{ $dept->parent?->name ?? '-' }}</td>
                            <td>
                                @forelse($dept->managers as $manager)
                                    <span class="badge badge-primary">{{ $manager->full_name }}</span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </td>
                            <td><span class="badge badge-info">{{ $dept->employees_count }}</span></td>
                            <td>
                                @if($dept->is_active)
                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('departments.edit', $dept) }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('messages.are_you_sure') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('messages.no_records_found') }} <a
                                    href="{{ route('departments.create') }}">{{ __('messages.add_one') }}</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-2">{{ $departments->links() }}</div>
        </div>
    </div>
@endsection