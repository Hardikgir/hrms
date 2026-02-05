@extends('layouts.adminlte')
@section('title', 'Assets')
@section('page_title', 'Assets')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Assets</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assets</h3>
        <div class="card-tools">
            @can('create', \App\Modules\Asset\Models\Asset::class)
            <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Asset</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3"><select name="employee_id" class="form-control"><option value="">All</option>@foreach($employees as $emp)<option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="status" class="form-control"><option value="">All</option><option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option><option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option></select></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Filter</button></div>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead><tr><th>Name</th><th>Type</th><th>Serial / Tag</th><th>Assigned To</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($assets as $a)
                @php $pendingReturn = $a->pendingReturnRequest(); @endphp
                <tr class="{{ $pendingReturn ? 'table-warning' : '' }}">
                    <td>{{ $a->name }}</td>
                    <td>{{ $a->assetType?->name ?? $a->type }}</td>
                    <td>{{ $a->serial_number ?? $a->asset_tag ?? '-' }}</td>
                    <td>{{ $a->employee->full_name ?? '-' }}</td>
                    <td>
                        {{ ucfirst(str_replace('_',' ',$a->status)) }}
                        @if($pendingReturn)
                            <span class="badge badge-warning ml-1">Return requested</span>
                        @endif
                    </td>
                    <td>
                        @can('update', $a)
                        <a href="{{ route('assets.edit', $a) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        @if($pendingReturn)
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal{{ $pendingReturn->id }}">Approve</button>
                            <div class="modal fade" id="approveModal{{ $pendingReturn->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('assets.return-requests.approve', $pendingReturn) }}" method="POST">
                                            @csrf
                                            <div class="modal-header"><h5 class="modal-title">Approve return</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">
                                                <p>Approve return of <strong>{{ $a->name }}</strong> from {{ $a->employee->full_name ?? '-' }}?</p>
                                                <div class="form-group">
                                                    <label for="approve_note_{{ $pendingReturn->id }}">Note (optional)</label>
                                                    <textarea class="form-control" id="approve_note_{{ $pendingReturn->id }}" name="admin_note" rows="2" placeholder="Optional note for records"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Approve return</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#declineModal{{ $pendingReturn->id }}">Decline</button>
                            <div class="modal fade" id="declineModal{{ $pendingReturn->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('assets.return-requests.decline', $pendingReturn) }}" method="POST">
                                            @csrf
                                            <div class="modal-header"><h5 class="modal-title">Decline return</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">
                                                <p>Decline return of <strong>{{ $a->name }}</strong>? The employee will see your note and can request return again.</p>
                                                <div class="form-group">
                                                    <label for="decline_note_{{ $pendingReturn->id }}">Note to employee <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('admin_note') is-invalid @enderror" id="decline_note_{{ $pendingReturn->id }}" name="admin_note" rows="3" required placeholder="e.g. Please hand over to IT first for data wipe"></textarea>
                                                    @error('admin_note')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Decline return</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif($a->status === 'available')
                        <form action="{{ route('assets.assign', $a) }}" method="POST" class="d-inline">@csrf<select name="employee_id" class="form-control form-control-sm d-inline-block w-auto" required>@foreach($employees as $emp)<option value="{{ $emp->id }}">{{ $emp->full_name }}</option>@endforeach</select><button type="submit" class="btn btn-sm btn-success">Assign</button></form>
                        @elseif($a->status === 'assigned')
                        <form action="{{ route('assets.unassign', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Unassign?');">@csrf<button type="submit" class="btn btn-sm btn-warning">Unassign</button></form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No assets.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $assets->links() }}
    </div>
</div>
@endsection
