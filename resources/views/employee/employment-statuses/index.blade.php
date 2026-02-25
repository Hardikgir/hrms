@extends('layouts.adminlte')

@section('title', __('messages.employment_statuses'))
@section('page_title', __('messages.employment_statuses'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.employment_statuses') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.employment_statuses') }}</h3>
            <div class="card-tools">
                <a href="{{ route('employment-statuses.create') }}" class="btn btn-primary btn-sm"><i
                        class="fas fa-plus"></i> {{ __('messages.add_status') }}</a>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small">{{ __('messages.employment_statuses_help') }}</p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.slug') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th>{{ __('messages.sort_order') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employmentStatuses as $status)
                        <tr>
                            <td>{{ $status->id }}</td>
                            <td>{{ $status->name }}</td>
                            <td><code>{{ $status->slug }}</code></td>
                            <td>{{ $status->description ?? '-' }}</td>
                            <td>{{ $status->sort_order }}</td>
                            <td>
                                @if($status->is_active)
                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('employment-statuses.edit', $status) }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('employment-statuses.destroy', $status) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('{{ __('messages.delete_employment_status_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('messages.no_employment_statuses') }} <a
                                    href="{{ route('employment-statuses.create') }}">{{ __('messages.add_one') }}</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-2">{{ $employmentStatuses->links() }}</div>
        </div>
    </div>
@endsection