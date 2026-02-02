<div>
    @if($this->todayAttendance && !$this->todayAttendance->check_out_time)
        <div class="alert alert-info">
            <h5 class="mb-1"><i class="icon fas fa-info"></i> You are currently checked in!</h5>
            <p class="mb-2">Check-in time: {{ $this->todayAttendance->check_in_time->format('h:i A') }}</p>
            <button type="button" wire:click="checkOut" wire:loading.attr="disabled" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span wire:loading.remove>Check Out</span>
                <span wire:loading>...</span>
            </button>
            @error('checkOut')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    @elseif(!$this->todayAttendance)
        <div class="alert alert-warning">
            <h5 class="mb-1"><i class="icon fas fa-exclamation-triangle"></i> You haven't checked in today!</h5>
            <button type="button" wire:click="checkIn" wire:loading.attr="disabled" class="btn btn-success mt-2">
                <i class="fas fa-sign-in-alt"></i>
                <span wire:loading.remove>Check In</span>
                <span wire:loading>...</span>
            </button>
            @error('checkIn')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    @else
        <div class="alert alert-success">
            <h5 class="mb-1"><i class="icon fas fa-check"></i> You have completed today's attendance!</h5>
            <p class="mb-0">
                Check-in: {{ $this->todayAttendance->check_in_time->format('h:i A') }} |
                Check-out: {{ $this->todayAttendance->check_out_time->format('h:i A') }}
            </p>
        </div>
    @endif
</div>
