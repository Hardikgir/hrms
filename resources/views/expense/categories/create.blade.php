@extends('layouts.adminlte')

@section('title', __('messages.add_category'))
@section('page_title', __('messages.add_category'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item"><a
            href="{{ route('expense-categories.index') }}">{{ __('messages.expense_categories') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.new_expense_category') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('expense-categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}" required maxlength="100"
                        placeholder="{{ __('messages.example_travel') }}">
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="slug">{{ __('messages.slug') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
                        value="{{ old('slug') }}" required maxlength="100"
                        placeholder="{{ __('messages.example_travel_slug') }}">
                    <small class="text-muted">{{ __('messages.slug_help') }}</small>
                    @error('slug')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="sort_order">{{ __('messages.sort_order') }}</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order"
                        value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">{{ __('messages.active') }}
                            ({{ __('messages.visible_to_employees') }})</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.create') }}</button>
                <a href="{{ route('expense-categories.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection