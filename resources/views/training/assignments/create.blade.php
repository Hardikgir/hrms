@extends('layouts.adminlte')
@section('title', 'Assign Training')
@section('page_title', 'Assign Training')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('training.assignments.index') }}">Assignments</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Assign Training</h3></div>
    <div class="card-body">
        <form action="{{ route('training.assignments.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="employee_id">Employee *</label><select class="form-control" id="employee_id" name="employee_id" required>@foreach($employees as $emp)<option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>@endforeach</select></div>
            <div class="form-group"><label for="training_course_id">Course *</label><select class="form-control" id="training_course_id" name="training_course_id" required>@foreach($courses as $c)<option value="{{ $c->id }}" {{ old('training_course_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select></div>
            <div class="form-group"><label for="due_date">Due Date</label><input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date') }}"></div>
            <button type="submit" class="btn btn-primary">Assign</button>
            <a href="{{ route('training.assignments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
