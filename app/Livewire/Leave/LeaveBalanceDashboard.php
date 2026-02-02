<?php

namespace App\Livewire\Leave;

use App\Modules\Leave\Services\LeaveService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LeaveBalanceDashboard extends Component
{
    public int $year;

    public function mount(?int $year = null): void
    {
        $this->year = $year ?? (int) now()->format('Y');
    }

    public function getDashboardProperty(): array
    {
        $user = auth()->user();
        $employee = $user->employee;
        if (!$employee) {
            return [];
        }
        return app(LeaveService::class)->getBalanceDashboardForEmployee($employee->id, $this->year);
    }

    public function render(): View
    {
        return view('livewire.leave.leave-balance-dashboard');
    }
}
