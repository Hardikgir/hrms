@extends('layouts.adminlte')

@section('title', __('messages.designations'))
@section('page_title', __('messages.designations'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.designations') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.designations') }}</h3>
            <div class="card-tools">
                <a href="{{ route('designations.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    {{ __('messages.add_designation') }}</a>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small">{{ __('messages.designations_list_help') }}</p>
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
                        <th>{{ __('messages.department') }}</th>
                        <th>{{ __('messages.employees_count') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $desig)
                        <tr>
                            <td>{{ $desig->id }}</td>
                            <td>{{ $desig->name }}</td>
                            <td><code>{{ $desig->code ?? '-' }}</code></td>
                            <td>{{ $desig->department?->name ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $desig->employees_count }}</span></td>
                            <td>
                                @if($desig->is_active)
                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('designations.edit', $desig) }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('designations.destroy', $desig) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('messages.are_you_sure') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('messages.no_records_found') }} <a
                                    href="{{ route('designations.create') }}">{{ __('messages.add_one') }}</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-2">{{ $designations->links() }}</div>
        </div>
    </div>
@endsection