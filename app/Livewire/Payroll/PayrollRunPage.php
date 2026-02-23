<?php

namespace App\Livewire\Payroll;

use App\Modules\Payroll\Services\PayrollService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PayrollRunPage extends Component
{
    public int $year;
    public int $month;
    public bool $runCompleted = false;
    public int $createdCount = 0;
    public string $statusMessage = '';

    public function mount(): void
    {
        $this->year = (int) now()->year;
        $this->month = (int) now()->month;
    }

    public function runPayroll(): void
    {
        $this->authorize('run payroll');

        $this->validate([
            'year' => 'required|integer|min:2020|max:2099',
            'month' => 'required|integer|min:1|max:12',
        ]);

        try {
            $created = app(PayrollService::class)->runPayrollForMonth($this->year, $this->month, auth()->id());
            $this->createdCount = count($created);
            $this->runCompleted = true;
            $this->statusMessage = $this->createdCount > 0
                ? __('messages.payroll_run_success', ['count' => $this->createdCount])
                : __('messages.payroll_run_no_records');
        } catch (\Exception $e) {
            $this->statusMessage = __('messages.error') . ': ' . $e->getMessage();
        }
    }

    public function render(): View
    {
        $this->authorize('run payroll');

        return view('livewire.payroll.payroll-run-page');
    }
}
