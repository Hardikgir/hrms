<div>
    @error('submit')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form wire:submit="submit">
        @if(!auth()->user()->employee)
            <div class="form-group">
                <label for="employee_id">Employee</label>
                <select wire:model="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                    <option value="">Select Employee</option>
                    @foreach($this->employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->employee_id }})</option>
                    @endforeach
                </select>
                @error('employee_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        @endif

        <div class="form-group">
            <label for="leave_type_id">Leave Type</label>
            <select wire:model="leave_type_id" id="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                <option value="">Select Leave Type</option>
                @foreach($this->leaveTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            @error('leave_type_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" wire:model="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" required>
                    @error('start_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" wire:model="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" required>
                    @error('end_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        @if($this->totalDays > 0)
            <p class="text-muted">Total days: <strong>{{ $this->totalDays }}</strong></p>
        @endif

        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea wire:model="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required placeholder="Reason for leave"></textarea>
            @error('reason')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                <span wire:loading.remove>Submit Leave Request</span>
                <span wire:loading>Submitting...</span>
            </button>
            @if(auth()->user()->employee)
                <a href="{{ route('ess.leaves') }}" class="btn btn-secondary">Cancel</a>
            @else
                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
            @endif
        </div>
    </form>
</div>
