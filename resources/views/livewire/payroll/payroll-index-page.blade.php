<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('messages.payroll_records') }}</h3>
            @can('run payroll')
                <a href="{{ route('payroll.run') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-calculator"></i> {{ __('messages.run_payroll') }}
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2">
                    <input type="number" wire:model.live="year" class="form-control"
                        placeholder="{{ __('messages.year') }}" min="2020" max="2099">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="month" class="form-control">
                        <option value="">{{ __('messages.all_months') }}</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="status" class="form-control">
                        <option value="">{{ __('messages.all_status') }}</option>
                        <option value="draft">{{ __('messages.draft') }}</option>
                        <option value="processed">{{ __('messages.processed') }}</option>
                        <option value="approved">{{ __('messages.approved') }}</option>
                        <option value="paid">{{ __('messages.paid') }}</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.period') }}</th>
                        <th>{{ __('messages.gross_salary') }}</th>
                        <th>{{ __('messages.deductions') }}</th>
                        <th>{{ __('messages.net_salary') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->employee->full_name ?? '-' }}</td>
                            <td>{{ $payroll->month && $payroll->year ? date('F Y', mktime(0, 0, 0, $payroll->month, 1)) : '-' }}
                            </td>
                            <td>{{ __('messages.currency_symbol') }}{{ number_format($payroll->gross_salary ?? 0, 2) }}</td>
                            <td>{{ __('messages.currency_symbol') }}{{ number_format($payroll->total_deductions ?? 0, 2) }}
                            </td>
                            <td>{{ __('messages.currency_symbol') }}{{ number_format($payroll->net_salary ?? 0, 2) }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'approved' ? 'info' : 'warning') }}">
                                    {{ __('messages.' . $payroll->status) }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-eye"></i> {{ __('messages.view') }}</a>
                                @if($payroll->status === 'draft' && auth()->user()->can('update payroll'))
                                    <form action="{{ route('payroll.lock', $payroll) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-lock"></i>
                                            {{ __('messages.lock') }}</button>
                                    </form>
                                @endif
                                @if(in_array($payroll->status, ['draft', 'processed']) && auth()->user()->can('update payroll'))
                                    <form action="{{ route('payroll.approve', $payroll) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i>
                                            {{ __('messages.approve') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('messages.no_payroll_records_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $payrolls->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>