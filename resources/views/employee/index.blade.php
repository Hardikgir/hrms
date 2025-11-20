@extends('layouts.adminlte')

@section('title', 'Employees')
@section('page_title', 'Employees')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Employees</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Employee List</h3>
        <div class="card-tools">
            @can('create employees')
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Employee
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="department_id" class="form-control">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table id="employeesTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->employee_id ?? '-' }}</td>
                    <td>{{ $employee->full_name ?? '-' }}</td>
                    <td>{{ $employee->email ?? '-' }}</td>
                    <td>{{ $employee->department->name ?? '-' }}</td>
                    <td>{{ $employee->designation->name ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ ($employee->employment_status ?? 'inactive') == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($employee->employment_status ?? 'inactive') }}
                        </span>
                    </td>
                    <td>
                        @can('view employees')
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endcan
                        @can('update employees')
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('delete employees')
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#employeesTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endpush

