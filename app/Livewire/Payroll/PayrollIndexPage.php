<?php

namespace App\Livewire\Payroll;

use App\Modules\Payroll\Services\PayrollService;
use App\Modules\Employee\Models\Employee;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollIndexPage extends Component
{
    use WithPagination;

    public ?string $year = null;
    public ?string $month = null;
    public ?string $status = null;

    protected $queryString = [
        'year' => ['except' => ''],
        'month' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount(): void
    {
        if ($this->year === null) {
            $this->year = (string) now()->year;
        }
    }

    public function getEmployeesProperty()
    {
        return Employee::where('is_active', true)->orderBy('first_name')->get();
    }

    public function updatedYear(): void
    {
        $this->resetPage();
    }

    public function updatedMonth(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render(PayrollService $payrollService): View
    {
        $this->authorize('view payroll');

        $filters = [
            'year' => $this->year ? (int) $this->year : null,
            'month' => $this->month ? (int) $this->month : null,
            'status' => $this->status ?: null,
        ];

        $payrolls = $payrollService->listPayroll($filters, 20, $this->getPage());

        return view('livewire.payroll.payroll-index-page', [
            'payrolls' => $payrolls,
        ]);
    }
}
