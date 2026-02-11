<div>
    @if($this->todayAttendance && !$this->todayAttendance->check_out_time)
        <div class="alert alert-info">
            <h5 class="mb-1"><i class="icon fas fa-info"></i> {{ __('messages.currently_checked_in') }}</h5>
            <p class="mb-2">{{ __('messages.check_in_time') }}: {{ $this->todayAttendance->check_in_time->format('h:i A') }}
            </p>
            <button type="button" wire:click="checkOut" wire:loading.attr="disabled" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span wire:loading.remove>{{ __('messages.check_out') }}</span>
                <span wire:loading>...</span>
            </button>
            @error('checkOut')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    @elseif(!$this->todayAttendance)
        <div class="alert alert-warning">
            <h5 class="mb-1"><i class="icon fas fa-exclamation-triangle"></i> {{ __('messages.not_checked_in_today') }}</h5>
            <button type="button" wire:click="checkIn" wire:loading.attr="disabled" class="btn btn-success mt-2">
                <i class="fas fa-sign-in-alt"></i>
                <span wire:loading.remove>{{ __('messages.check_in') }}</span>
                <span wire:loading>...</span>
            </button>
            @error('checkIn')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    @else
        <div class="alert alert-success">
            <h5 class="mb-1"><i class="icon fas fa-check"></i> {{ __('messages.completed_attendance') }}</h5>
            <p class="mb-0">
                {{ __('messages.check_in') }}: {{ $this->todayAttendance->check_in_time->format('h:i A') }} |
                {{ __('messages.check_out') }}: {{ $this->todayAttendance->check_out_time->format('h:i A') }}
            </p>
        </div>
    @endif
</div>