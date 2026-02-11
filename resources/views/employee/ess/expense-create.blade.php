@extends('layouts.ess')

@section('title', __('messages.submit_expense'))
@section('page_title', __('messages.submit_expense'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.expenses') }}">{{ __('messages.expenses') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.submit') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.submit_expense') }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.expenses') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_expenses') }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('ess.expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="amount">{{ __('messages.amount') }} <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount"
                        name="amount" value="{{ old('amount') }}" required>
                    @error('amount')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="category">{{ __('messages.category') }} <span class="text-danger">*</span></label>
                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category"
                        required>
                        <option value="">{{ __('messages.select') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ old('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @if($categories->isEmpty())
                        <small class="text-muted">{{ __('messages.no_categories_yet') }}</small>
                    @endif
                    @error('category')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="description">{{ __('messages.description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="2">{{ old('description') }}</textarea>
                    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="receipt">{{ __('messages.receipt') }} ({{ __('messages.optional') }})</label>
                    <input type="file" class="form-control-file" id="receipt" name="receipt" accept=".pdf,.jpg,.jpeg,.png">
                    @error('receipt')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
                <a href="{{ route('ess.expenses') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection