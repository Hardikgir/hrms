@extends('layouts.adminlte')

@section('title', __('messages.performance_management'))
@section('page_title', __('messages.performance_management'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.performance') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.review_cycles') }}</h3>
            <div class="card-tools">
                @can('manage performance')
                    <a href="{{ route('performance.cycles.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('messages.new_cycle') }}
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('performance.cycles.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_statuses') }}</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                {{ __('messages.draft') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                {{ __('messages.active') }}</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>
                                {{ __('messages.closed') }}</option>
                        </select>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.period') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.reviews') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cycles as $cycle)
                        <tr>
                            <td>{{ $cycle->id }}</td>
                            <td>{{ $cycle->name }}</td>
                            <td>{{ $cycle->period_start->format('d M Y') }} – {{ $cycle->period_end->format('d M Y') }}</td>
                            <td>
                                @if($cycle->status === 'draft')
                                    <span class="badge badge-secondary">{{ __('messages.draft') }}</span>
                                @elseif($cycle->status === 'active')
                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('messages.closed') }}</span>
                                @endif
                            </td>
                            <td>{{ $cycle->reviews()->count() }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('performance.cycles.show', $cycle) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-eye"></i></a>
                                @can('update', $cycle)
                                    <a href="{{ route('performance.cycles.edit', $cycle) }}" class="btn btn-sm btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                @endcan
                                @can('delete', $cycle)
                                    <form action="{{ route('performance.cycles.destroy', $cycle) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('{{ __('messages.delete_cycle_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                {{ __('messages.no_cycles_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $cycles->links() }}</div>
        </div>
    </div>
@endsection