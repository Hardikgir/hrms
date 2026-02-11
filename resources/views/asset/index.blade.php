@extends('layouts.adminlte')
@section('title', __('messages.assets'))
@section('page_title', __('messages.assets'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.assets') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.assets') }}</h3>
            <div class="card-tools">
                @can('create', \App\Modules\Asset\Models\Asset::class)
                    <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                        {{ __('messages.new_asset') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-3 filter-form">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="employee_id" class="form-control">
                            <option value="">{{ __('messages.all') }}</option>@foreach($employees as $emp)<option
                                value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select name="status" class="form-control">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                                {{ __('messages.available') }}</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>
                                {{ __('messages.assigned') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.type') }}</th>
                        <th>{{ __('messages.serial_tag') }}</th>
                        <th>{{ __('messages.assigned_to') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $a)
                        @php $pendingReturn = $a->pendingReturnRequest(); @endphp
                        <tr class="{{ $pendingReturn ? 'table-warning' : '' }}">
                            <td>{{ $a->name }}</td>
                            <td>{{ $a->assetType?->name ?? $a->type }}</td>
                            <td>{{ $a->serial_number ?? $a->asset_tag ?? '-' }}</td>
                            <td>{{ $a->employee->full_name ?? '-' }}</td>
                            <td>
                                {{ __('messages.' . $a->status) }}
                                @if($pendingReturn)
                                    <span class="badge badge-warning ml-1">{{ __('messages.return_requested') }}</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                @can('view', $a)
                                    <a href="{{ route('assets.history', $a) }}" class="btn btn-sm btn-info"
                                        title="Assignment history"><i class="fas fa-history"></i></a>
                                @endcan
                                @can('update', $a)
                                    <a href="{{ route('assets.edit', $a) }}" class="btn btn-sm btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                @endcan
                                @can('approveReturn', $a)
                                    @if($pendingReturn)
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                            data-target="#approveModal{{ $pendingReturn->id }}">{{ __('messages.approve') }}</button>
                                        <div class="modal fade" id="approveModal{{ $pendingReturn->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('assets.return-requests.approve', $pendingReturn) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('messages.approve_return') }}</h5><button
                                                                type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('messages.approve_return_confirm', ['name' => $a->name, 'employee' => $a->employee->full_name ?? '-']) }}
                                                            </p>
                                                            <div class="form-group">
                                                                <label
                                                                    for="approve_note_{{ $pendingReturn->id }}">{{ __('messages.note_optional') }}</label>
                                                                <textarea class="form-control"
                                                                    id="approve_note_{{ $pendingReturn->id }}" name="admin_note"
                                                                    rows="2"
                                                                    placeholder="{{ __('messages.optional_note_placeholder') }}"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ __('messages.cancel') }}</button><button
                                                                type="submit"
                                                                class="btn btn-success">{{ __('messages.approve_return') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#declineModal{{ $pendingReturn->id }}">{{ __('messages.decline') }}</button>
                                        <div class="modal fade" id="declineModal{{ $pendingReturn->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('assets.return-requests.decline', $pendingReturn) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('messages.decline_return') }}</h5><button
                                                                type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ __('messages.decline_return_confirm', ['name' => $a->name]) }}</p>
                                                            <div class="form-group">
                                                                <label
                                                                    for="decline_note_{{ $pendingReturn->id }}">{{ __('messages.note_to_employee') }}
                                                                    <span class="text-danger">*</span></label>
                                                                <textarea class="form-control @error('admin_note') is-invalid @enderror"
                                                                    id="decline_note_{{ $pendingReturn->id }}" name="admin_note"
                                                                    rows="3" required
                                                                    placeholder="{{ __('messages.decline_note_placeholder') }}"></textarea>
                                                                @error('admin_note')<span
                                                                class="invalid-feedback">{{ $message }}</span>@enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ __('messages.cancel') }}</button><button
                                                                type="submit"
                                                                class="btn btn-danger">{{ __('messages.decline_return') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endcan
                                @can('update', $a)
                                    @if(!$pendingReturn && $a->status === 'available')
                                        <form action="{{ route('assets.assign', $a) }}" method="POST" class="d-inline">@csrf<select
                                                name="employee_id" class="form-control form-control-sm d-inline-block w-auto"
                                                required>@foreach($employees as $emp)<option value="{{ $emp->id }}">
                                                {{ $emp->full_name }}</option>@endforeach</select><button type="submit"
                                                class="btn btn-sm btn-success">{{ __('messages.assign') }}</button></form>
                                    @elseif(!$pendingReturn && $a->status === 'assigned')
                                        <form action="{{ route('assets.unassign', $a) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('{{ __('messages.confirm_unassign') }}');">@csrf<button
                                                type="submit" class="btn btn-sm btn-warning">{{ __('messages.unassign') }}</button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_assets') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $assets->links() }}
        </div>
    </div>
@endsection