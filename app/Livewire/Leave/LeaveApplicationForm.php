<?php

namespace App\Livewire\Leave;

use App\Modules\Leave\Services\LeaveService;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Employee\Models\Employee;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LeaveApplicationForm extends Component
{
    public ?string $employee_id = null;
    public string $leave_type_id = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $reason = '';

    public function mount(): void
    {
        $user = auth()->user();
        $employee = $user->employee;
        if ($employee) {
            $this->employee_id = (string) $employee->id;
        }
    }

    public function getLeaveTypesProperty()
    {
        return LeaveType::where('is_active', true)->get();
    }

    public function getEmployeesProperty()
    {
        $user = auth()->user();
        $employee = $user->employee;
        if ($employee) {
            return collect([$employee]);
        }
        return Employee::where('is_active', true)->orderBy('first_name')->get();
    }

    public function getTotalDaysProperty(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        try {
            return LeaveService::calculateTotalDays($this->start_date, $this->end_date);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function updatedStartDate(): void
    {
        if ($this->end_date && $this->start_date > $this->end_date) {
            $this->end_date = $this->start_date;
        }
    }

    public function submit(): void
    {
        $this->authorize('create leaves');

        $rules = [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:2000',
        ];
        $user = auth()->user();
        $employee = $user->employee;
        if (!$employee) {
            $rules['employee_id'] = 'required|exists:employees,id';
        }

        $this->validate($rules);

        $employeeId = $employee ? $employee->id : (int) $this->employee_id;
        if ($employee && (int) $this->employee_id !== $employee->id) {
            $this->addError('employee_id', 'Unauthorized.');
            return;
        }

        try {
            app(LeaveService::class)->apply(
                $employeeId,
                (int) $this->leave_type_id,
                $this->start_date,
                $this->end_date,
                $this->reason,
                $user->id
            );
        } catch (\DomainException|\InvalidArgumentException $e) {
            $this->addError('submit', $e->getMessage());
            return;
        }

        session()->flash('success', 'Leave request created successfully.');
        if ($employee) {
            $this->redirect(route('ess.leaves'), navigate: true);
        } else {
            $this->redirect(route('leaves.index'), navigate: true);
        }
    }

    public function render(): View
    {
        return view('livewire.leave.leave-application-form');
    }
}
