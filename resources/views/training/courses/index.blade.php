@extends('layouts.adminlte')
@section('title', 'Training Courses')
@section('page_title', 'Training Courses')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Training Courses</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Training Courses</h3>
        <div class="card-tools">
            @can('create', \App\Modules\Training\Models\TrainingCourse::class)
            <a href="{{ route('training.courses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Course</a>
            @endcan
            <a href="{{ route('training.assignments.index') }}" class="btn btn-secondary btn-sm">Assignments</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Name</th><th>Type</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($courses as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->type ?? '-' }}</td>
                    <td>{{ $c->duration_hours ?? 0 }} hrs</td>
                    <td>{{ $c->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        @can('update', $c)
                        <a href="{{ route('training.courses.edit', $c) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No courses.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $courses->links() }}
    </div>
</div>
@endsection
