<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Run Payroll</h3>
        </div>
        <div class="card-body">
            <p class="text-muted">Select the month and year to run payroll. Draft records will be created for all active employees based on attendance and salary structure.</p>

            <form wire:submit="runPayroll" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="year">Year</label>
                        <input type="number" wire:model="year" id="year" class="form-control" min="2020" max="2099">
                        @error('year')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="month">Month</label>
                        <select wire:model="month" id="month" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                        @error('month')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                            <span wire:loading.remove>Run Payroll</span>
                            <span wire:loading>Running...</span>
                        </button>
                    </div>
                </div>
            </form>

            @if($message)
                <div class="alert {{ $runCompleted ? 'alert-success' : 'alert-danger' }}">
                    {{ $message }}
                </div>
            @endif

            @if($runCompleted && $createdCount > 0)
                <a href="{{ route('payroll.index') }}?year={{ $year }}&month={{ $month }}" class="btn btn-secondary">View Payroll List</a>
            @endif
        </div>
    </div>
</div>
