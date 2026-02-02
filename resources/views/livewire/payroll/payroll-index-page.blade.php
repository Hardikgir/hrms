<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Payroll Records</h3>
            @can('run payroll')
                <a href="{{ route('payroll.run') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-calculator"></i> Run Payroll
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2">
                    <input type="number" wire:model.live="year" class="form-control" placeholder="Year" min="2020" max="2099">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="month" class="form-control">
                        <option value="">All Months</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="processed">Processed</option>
                        <option value="approved">Approved</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Gross Salary</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->employee->full_name ?? '-' }}</td>
                            <td>{{ $payroll->month && $payroll->year ? date('F Y', mktime(0, 0, 0, $payroll->month, 1)) : '-' }}</td>
                            <td>₹{{ number_format($payroll->gross_salary ?? 0, 2) }}</td>
                            <td>₹{{ number_format($payroll->total_deductions ?? 0, 2) }}</td>
                            <td>₹{{ number_format($payroll->net_salary ?? 0, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'approved' ? 'info' : 'warning') }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
</div>
