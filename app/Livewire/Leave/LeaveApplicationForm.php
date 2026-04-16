<?php

namespace App\Livewire\Leave;

use App\Modules\Leave\Models\Leave;
use App\Modules\Leave\Services\LeaveService;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Employee\Models\Employee;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeaveApplicationForm extends Component
{
    use WithFileUploads;

    public ?string $employee_id = null;
    public ?Leave $leave = null;
    public string $leave_type_id = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $reason = '';
    public $attachment;

    // Read-only/Auto-fetched Employee Info
    public string $employee_name = '';
    public string $id_no = '';
    public string $nationality = '';
    public string $position_title = '';
    public string $department_name = '';
    public string $date_of_hire = '';
    public string $end_of_contract = '';

    // Entitlement and Last Leave info
    public int $accrued_entitlement = 0;
    public string $last_leave_type = '';
    public string $last_leave_from = '';
    public string $last_leave_to = '';

    // Visa Details
    public bool $is_hospital_paid = false;
    public bool $is_employee_paid = false;
    public array $family_visa_details = [];

    // Contact Info
    public string $contact_address = '';
    public string $contact_phone = '';
    public string $contact_mobile = '';
    public string $contact_email = '';

    public function mount($leave = null): void
    {
        $this->leave = $leave;
        $user = auth()->user();
        $employee = $user->employee;

        if ($this->leave && $this->leave->exists) {
            // Edit Mode
            $this->employee_id = (string) $this->leave->employee_id;
            $this->leave_type_id = (string) $this->leave->leave_type_id;
            $this->start_date = $this->leave->start_date?->toDateString() ?? '';
            $this->end_date = $this->leave->end_date?->toDateString() ?? '';
            $this->reason = $this->leave->reason;

            $employee = $this->leave->employee;
            if ($employee) {
                $this->loadEmployeeData($employee);
            }

            $extra = $this->leave->extraDetails;
            if ($extra) {
                $this->is_hospital_paid = $extra->is_hospital_paid;
                $this->is_employee_paid = $extra->is_employee_paid;
                $this->family_visa_details = $extra->family_visa_details ?? [];
                $this->contact_address = $extra->contact_address ?? '';
                $this->contact_phone = $extra->contact_phone ?? '';
                $this->contact_mobile = $extra->contact_mobile ?? '';
                $this->contact_email = $extra->contact_email ?? '';
            }
        } elseif ($employee) {
            // Create Mode
            $this->employee_id = (string) $employee->id;
            $this->loadEmployeeData($employee);
        }

        // Initialize family members with empty rows if empty (common for both modes)
        if (empty($this->family_visa_details)) {
            $this->family_visa_details = [
                ['name' => '', 'relationship' => 'Employee', 'exit_reentry' => false, 'final_exit' => false],
                ['name' => '', 'relationship' => 'Spouse', 'exit_reentry' => false, 'final_exit' => false],
                ['name' => '', 'relationship' => 'Child', 'exit_reentry' => false, 'final_exit' => false],
                ['name' => '', 'relationship' => 'Child', 'exit_reentry' => false, 'final_exit' => false],
            ];
        }
    }

    public function updatedLeaveTypeId($value): void
    {
        if ($value && $this->employee_id) {
            $leaveService = app(LeaveService::class);
            $balance = $leaveService->getBalanceForEmployee((int)$this->employee_id, (int)$value, (int) date('Y'));
            $this->accrued_entitlement = $balance['available'];
        }
    }

    public function updatedEmployeeId($value): void
    {
        if ($value) {
            $employee = Employee::find($value);
            if ($employee) {
                $this->loadEmployeeData($employee);
            }
        }
    }

    protected function loadEmployeeData(Employee $employee): void
    {
        $this->employee_name = $employee->full_name;
        $this->id_no = $employee->employee_id;
        $this->nationality = $employee->nationality ?? 'N/A';
        $this->position_title = $employee->designation ? $employee->designation->name : 'N/A';
        $this->department_name = $employee->department ? $employee->department->name : 'N/A';
        $this->date_of_hire = $employee->joining_date ? $employee->joining_date->format('Y-m-d') : 'N/A';
        $this->end_of_contract = $employee->contract_end_date ? $employee->contract_end_date->format('Y-m-d') : 'N/A';

        // Contact Info defaults
        $this->contact_address = $employee->address ?? '';
        $this->contact_phone = $employee->phone ?? '';
        $this->contact_mobile = $employee->phone ?? '';
        $this->contact_email = $employee->email ?? '';

        // Fetch Last Leave and Entitlement
        $leaveService = app(LeaveService::class);
        $lastLeave = $leaveService->getLastApprovedLeave($employee->id);
        if ($lastLeave) {
            $this->last_leave_type = $lastLeave->leaveType->name;
            $this->last_leave_from = $lastLeave->start_date->format('Y-m-d');
            $this->last_leave_to = $lastLeave->end_date->format('Y-m-d');
        }

        // Entitlement - Usually based on the selected type, but we fetch the first active type as a placeholder or Annual Leave if exists
        $annualLeaveType = LeaveType::where('name', 'like', '%Annual%')->first() ?? LeaveType::where('is_active', true)->first();
        if ($annualLeaveType) {
            $balance = $leaveService->getBalanceForEmployee($employee->id, $annualLeaveType->id, (int) date('Y'));
            $this->accrued_entitlement = $balance['available'];
        }
    }

    public function addFamilyMember(): void
    {
        $this->family_visa_details[] = ['name' => '', 'relationship' => '', 'exit_reentry' => false, 'final_exit' => false];
    }

    public function removeFamilyMember(int $index): void
    {
        unset($this->family_visa_details[$index]);
        $this->family_visa_details = array_values($this->family_visa_details);
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
        if ($this->leave) {
            $this->authorize('update', $this->leave);
        } else {
            $this->authorize('create', Leave::class);
        }

        $rules = [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:2000',
            'attachment' => 'nullable|file|image|mimes:jpeg,png,jpg|max:5120',
            'is_hospital_paid' => 'boolean',
            'is_employee_paid' => 'boolean',
            'family_visa_details' => 'array',
            'contact_address' => 'required|string|max:1000',
            'contact_phone' => 'nullable|string|max:20',
            'contact_mobile' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
        ];
        $user = auth()->user();
        $employee = $user->employee;
        if (!$employee && !$this->leave) {
            $rules['employee_id'] = 'required|exists:employees,id';
        }

        $this->validate($rules);

        $employeeId = $employee ? $employee->id : (int) $this->employee_id;
        
        $attachmentPath = $this->leave ? $this->leave->attachment_path : null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('leaves/attachments', 'public');
        }

        $extraDetails = [
            'is_hospital_paid' => $this->is_hospital_paid,
            'is_employee_paid' => $this->is_employee_paid,
            'family_visa_details' => $this->family_visa_details,
            'contact_address' => $this->contact_address,
            'contact_phone' => $this->contact_phone,
            'contact_mobile' => $this->contact_mobile,
            'contact_email' => $this->contact_email,
        ];

        try {
            $leaveService = app(LeaveService::class);
            if ($this->leave) {
                $leaveService->update(
                    $this->leave->id,
                    (int) $this->leave_type_id,
                    $this->start_date,
                    $this->end_date,
                    $this->reason,
                    $user->id,
                    $attachmentPath,
                    $extraDetails
                );
            } else {
                $leaveService->apply(
                    $employeeId,
                    (int) $this->leave_type_id,
                    $this->start_date,
                    $this->end_date,
                    $this->reason,
                    $user->id,
                    $attachmentPath,
                    $extraDetails
                );
            }
        } catch (\DomainException|\InvalidArgumentException $e) {
            $this->addError('submit', $e->getMessage());
            return;
        }

        session()->flash('success', $this->leave ? 'Leave request updated successfully.' : 'Leave request created successfully.');
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
