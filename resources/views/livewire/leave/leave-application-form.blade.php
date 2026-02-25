<div>
    @error('submit')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form wire:submit="submit">
        @if(!auth()->user()->employee)
            <div class="form-group">
                <label for="employee_id">{{ __('messages.employee') }}</label>
                <select wire:model="employee_id" id="employee_id"
                    class="form-control @error('employee_id') is-invalid @enderror" required>
                    <option value="">{{ __('messages.select_employee') }}</option>
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
            <label for="leave_type_id">{{ __('messages.leave_type') }}</label>
            <select wire:model="leave_type_id" id="leave_type_id"
                class="form-control @error('leave_type_id') is-invalid @enderror" required>
                <option value="">{{ __('messages.select_leave_type') }}</option>
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
                    <label for="start_date">{{ __('messages.start_date') }}</label>
                    <input type="date" wire:model="start_date" id="start_date"
                        class="form-control @error('start_date') is-invalid @enderror" required>
                    @error('start_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date">{{ __('messages.end_date') }}</label>
                    <input type="date" wire:model="end_date" id="end_date"
                        class="form-control @error('end_date') is-invalid @enderror" required>
                    @error('end_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        @if($this->totalDays > 0)
            <p class="text-muted">{{ __('messages.total_days') }}: <strong>{{ $this->totalDays }}</strong></p>
        @endif

        <div class="form-group">
            <label for="reason">{{ __('messages.reason') }}</label>
            <textarea wire:model="reason" id="reason" class="form-control @error('reason') is-invalid @enderror"
                rows="3" required placeholder="{{ __('messages.reason') }}"></textarea>
            @error('reason')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="attachment">{{ __('messages.physical_form_photograph') }} <span class="text-danger">*</span></label>
            <input type="file" wire:model="attachment" id="attachment" class="form-control-file @error('attachment') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg" required>
            <small class="form-text text-muted">{{ __('messages.upload_physical_form_desc') }}</small>
            @error('attachment')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <div wire:loading wire:target="attachment" class="text-info mt-1">{{ __('messages.uploading') }}...</div>
        </div>

        <div class="form-group">
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                <span wire:loading.remove>{{ __('messages.apply_leave') }}</span>
                <span wire:loading>{{ __('messages.submitting') }}</span>
            </button>
            @if(auth()->user()->employee)
                <a href="{{ route('ess.leaves') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            @else
                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            @endif
        </div>
    </form>
</div>