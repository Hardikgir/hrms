@extends('layouts.adminlte')

@section('title', 'Submit Expense')
@section('page_title', 'Submit Expense')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
    <li class="breadcrumb-item active">Submit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Submit Expense</h3></div>
    <div class="card-body">
        <form action="{{ request()->routeIs('ess.*') ? route('ess.expenses.store') : route('expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                @error('amount')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="category">Category <span class="text-danger">*</span></label>
                <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                    <option value="">Select</option>
                    <option value="travel" {{ old('category') == 'travel' ? 'selected' : '' }}>Travel</option>
                    <option value="meals" {{ old('category') == 'meals' ? 'selected' : '' }}>Meals</option>
                    <option value="supplies" {{ old('category') == 'supplies' ? 'selected' : '' }}>Supplies</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="receipt">Receipt (optional)</label>
                <input type="file" class="form-control-file" id="receipt" name="receipt" accept=".pdf,.jpg,.jpeg,.png">
                @error('receipt')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ request()->routeIs('ess.*') ? route('ess.expenses') : route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
