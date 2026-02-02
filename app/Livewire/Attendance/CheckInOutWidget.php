<?php

namespace App\Livewire\Attendance;

use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CheckInOutWidget extends Component
{
    /**
     * For ESS: current user's employee is used. No props required.
     */
    public function getTodayAttendanceProperty(): ?\App\Modules\Attendance\Models\Attendance
    {
        $user = auth()->user();
        $employee = $user?->employee;
        if (!$employee) {
            return null;
        }
        return app(AttendanceService::class)->getTodayAttendanceForEmployee($employee->id);
    }

    public function checkIn(): void
    {
        $user = auth()->user();
        $employee = $user->employee;
        if (!$employee) {
            return;
        }
        try {
            app(AttendanceService::class)->checkIn($employee->id, 'web', null, null, $user->id);
            $this->dispatch('attendance-updated');
        } catch (\DomainException $e) {
            $this->addError('checkIn', $e->getMessage());
        }
    }

    public function checkOut(): void
    {
        $user = auth()->user();
        $employee = $user->employee;
        if (!$employee) {
            return;
        }
        $today = $this->todayAttendance;
        if (!$today || $today->check_out_time) {
            return;
        }
        try {
            app(AttendanceService::class)->checkOut(
                $today->id,
                $employee->id,
                'web',
                null,
                null,
                $user->id
            );
            $this->dispatch('attendance-updated');
        } catch (\DomainException|\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->addError('checkOut', $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.attendance.check-in-out-widget');
    }
}
