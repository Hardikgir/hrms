@extends('layouts.adminlte')

@section('title', __('messages.asset_types'))
@section('page_title', __('messages.asset_types'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.asset_types') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.asset_types') }}</h3>
            <div class="card-tools">
                <a href="{{ route('asset-types.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    {{ __('messages.add_type') }}</a>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small">{{ __('messages.asset_types_help') }}</p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.slug') }}</th>
                        <th>{{ __('messages.sort_order') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assetTypes as $type)
                        <tr>
                            <td>{{ $type->id }}</td>
                            <td>{{ $type->name }}</td>
                            <td><code>{{ $type->slug }}</code></td>
                            <td>{{ $type->sort_order }}</td>
                            <td>
                                @if($type->is_active)
                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('asset-types.edit', $type) }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('asset-types.destroy', $type) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('messages.delete_type_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_asset_types') }} <a
                                    href="{{ route('asset-types.create') }}">{{ __('messages.add_one') }}</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-2">{{ $assetTypes->links() }}</div>
        </div>
    </div>
@endsection