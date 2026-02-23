@extends('layouts.adminlte')

@section('title', __('messages.expense_categories'))
@section('page_title', __('messages.expense_categories'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.expense_categories') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.expense_categories') }}</h3>
            <div class="card-tools">
                <a href="{{ route('expense-categories.create') }}" class="btn btn-primary btn-sm"><i
                        class="fas fa-plus"></i> {{ __('messages.add_category') }}</a>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small">{{ __('messages.expense_category_help') }}</p>
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
                    @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td>{{ $cat->name }}</td>
                            <td><code>{{ $cat->slug }}</code></td>
                            <td>{{ $cat->sort_order }}</td>
                            <td>
                                @if($cat->is_active)
                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('expense-categories.edit', $cat) }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('expense-categories.destroy', $cat) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('messages.are_you_sure') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_records_found') }} <a
                                    href="{{ route('expense-categories.create') }}">{{ __('messages.add_one') }}</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-2">{{ $categories->links() }}</div>
        </div>
    </div>
@endsection