@extends('layouts.adminlte')

@section('title', __('messages.employees'))
@section('page_title', __('messages.employees'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.employees') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.employee_list') }}</h3>
            <div class="card-tools">
                @can('create employees')
                    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('messages.add_employee') }}
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('employees.index') }}" class="mb-3 filter-form" id="employeesFilterForm">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <input type="text" name="search" class="form-control" id="employeesSearchInput"
                            placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select name="department_id" class="form-control" id="employeesDepartmentFilter">
                            <option value="">{{ __('messages.all_departments') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select name="status" class="form-control" id="employeesStatusFilter">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                {{ __('messages.active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">{{ __('messages.reset') }}</a>
                    </div>
                </div>
            </form>

            <table id="employeesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee_id') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.department') }}</th>
                        <th>{{ __('messages.designation') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
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
                                <span
                                    class="badge badge-{{ ($employee->employment_status ?? 'inactive') == 'active' ? 'success' : 'secondary' }}">
                                    {{ __('messages.' . ($employee->employment_status ?? 'inactive')) }}
                                </span>
                            </td>
                            <td class="action-buttons">
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
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('{{ __('messages.are_you_sure') }}');">
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
                            <td colspan="7" class="text-center">{{ __('messages.no_records_found') }}</td>
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
        $(document).ready(function () {
            // Auto-apply filters (no need to click Filter button)
            var filterForm = $('#employeesFilterForm');
            var searchInput = $('#employeesSearchInput');
            var deptFilter = $('#employeesDepartmentFilter');
            var statusFilter = $('#employeesStatusFilter');

            var searchDebounceTimer = null;
            var lastSearchValue = searchInput.val();

            function submitFilters() {
                // Avoid submitting if nothing changed (mainly for search debounce)
                filterForm.trigger('submit');
            }

            deptFilter.on('change', function () {
                submitFilters();
            });

            statusFilter.on('change', function () {
                submitFilters();
            });

            searchInput.on('input', function () {
                var v = $(this).val();
                if (v === lastSearchValue) return;
                lastSearchValue = v;

                if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(function () {
                    submitFilters();
                }, 400);
            });

            searchInput.on('keydown', function (e) {
                if (e.key === 'Enter') {
                    if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
                    submitFilters();
                }
            });

            var table = $('#employeesTable');
            var tbody = table.find('tbody');
            var dataRows = tbody.find('tr').filter(function () {
                return $(this).find('td[colspan]').length === 0;
            });

            // Only initialize DataTable if there are actual data rows
            if (dataRows.length > 0) {
                // Remove empty row with colspan before initializing DataTable
                tbody.find('tr').each(function () {
                    if ($(this).find('td[colspan]').length > 0) {
                        $(this).remove();
                    }
                });

                table.DataTable({
                    "paging": false,
                    "searching": false,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    "columnDefs": [
                        { "orderable": false, "targets": 6 } // Disable sorting on Actions column (index 6)
                    ]
                });
            }
        });
    </script>
@endpush