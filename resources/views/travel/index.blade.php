@extends('layouts.adminlte')
@section('title', __('messages.travel_requests'))
@section('page_title', __('messages.travel_requests'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.travel') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.travel_requests') }}</h3>
            <div class="card-tools">
                @if(auth()->user()->employee)
                    <a href="{{ route('travel.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                        {{ __('messages.new_request') }}</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($employees->isNotEmpty())
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3"><select name="employee_id" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>@foreach($employees as $emp)<option
                                    value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }}</option>@endforeach
                            </select></div>
                        <div class="col-md-2"><select name="status" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    {{ __('messages.approved') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    {{ __('messages.rejected') }}</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    {{ __('messages.completed') }}</option>
                            </select></div>
                        <div class="col-md-2"><button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                        </div>
                    </div>
                </form>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.purpose') }}</th>
                        <th>{{ __('messages.dates') }}</th>
                        <th>{{ __('messages.estimated') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        <tr>
                            <td>{{ $r->employee->full_name ?? '-' }}</td>
                            <td>{{ Str::limit($r->purpose, 30) }}</td>
                            <td>{{ $r->start_date->format('d M') }} – {{ $r->end_date->format('d M Y') }}</td>
                            <td>{{ $r->estimated_amount ? number_format($r->estimated_amount, 2) : '-' }}</td>
                            <td>@if($r->status === 'pending')<span
                            class="badge badge-warning">{{ __('messages.pending') }}</span>@elseif($r->status === 'approved')<span
                                    class="badge badge-info">{{ __('messages.approved') }}</span>@elseif($r->status === 'rejected')<span
                                    class="badge badge-danger">{{ __('messages.rejected') }}</span>@else<span
                                    class="badge badge-success">{{ __('messages.completed') }}</span>@endif</td>
                            <td class="action-buttons">
                                <a href="{{ route('travel.show', $r) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-eye"></i></a>
                                @can('approve', $r)
                                    @if($r->status === 'pending')
                                        <form action="{{ route('travel.approve', $r) }}" method="POST" class="d-inline">@csrf<button
                                                type="submit" class="btn btn-sm btn-success">{{ __('messages.approve') }}</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#rejectModal{{ $r->id }}">{{ __('messages.reject') }}</button>
                                    @endif
                                    @if($r->status === 'approved')
                                        <form action="{{ route('travel.complete', $r) }}" method="POST" class="d-inline">@csrf<button
                                                type="submit"
                                                class="btn btn-sm btn-primary">{{ __('messages.mark_completed') }}</button></form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_travel_requests') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $requests->links() }}
        </div>
    </div>
    @foreach($requests->where('status', 'pending') as $r)
        @can('approve', $r)
            <div class="modal fade" id="rejectModal{{ $r->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('travel.reject', $r) }}" method="POST">@csrf
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('messages.reject_request') }}</h5><button type="button" class="close"
                                    data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group"><label for="reason{{ $r->id }}">{{ __('messages.reason') }}
                                        *</label><textarea class="form-control" id="reason{{ $r->id }}" name="reason" rows="3"
                                        required></textarea></div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('messages.cancel') }}</button><button type="submit"
                                    class="btn btn-danger">{{ __('messages.reject') }}</button></div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endforeach
@endsection