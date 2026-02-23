<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.run_payroll') }}</h3>
        </div>
        <div class="card-body">
            <p class="text-muted">{{ __('messages.payroll_run_help') }}</p>

            <form wire:submit="runPayroll" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="year">{{ __('messages.year') }}</label>
                        <input type="number" wire:model="year" id="year" class="form-control" min="2020" max="2099">
                        @error('year')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="month">{{ __('messages.month') }}</label>
                        <select wire:model="month" id="month" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ __('messages.' . strtolower(date('F', mktime(0, 0, 0, $i, 1)))) }}</option>
                            @endfor
                        </select>
                        @error('month')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                            <span wire:loading.remove>{{ __('messages.run_payroll') }}</span>
                            <span wire:loading>{{ __('messages.running') }}</span>
                        </button>
                    </div>
                </div>
            </form>

            @if($statusMessage)
                <div class="alert {{ $runCompleted ? 'alert-success' : 'alert-danger' }}">
                    {{ $statusMessage }}
                </div>
            @endif

            @if($runCompleted && $createdCount > 0)
                <a href="{{ route('payroll.index') }}?year={{ $year }}&month={{ $month }}"
                    class="btn btn-secondary">{{ __('messages.view_payroll_list') }}</a>
            @endif
        </div>
    </div>
</div>