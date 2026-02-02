<?php

namespace App\Livewire\Leave;

use App\Modules\Leave\Models\Leave;
use App\Modules\Leave\Services\LeaveService;
use App\Modules\Employee\Models\Employee;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveIndexPage extends Component
{
    use WithPagination;

    public ?string $search = null;
    public ?string $status = null;

    public ?int $rejectLeaveId = null;
    public string $rejectionReason = '';
    public bool $showRejectModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function getLeaveTypesProperty()
    {
        return LeaveType::where('is_active', true)->get();
    }

    public function getEmployeesProperty()
    {
        return Employee::orderBy('first_name')->get();
    }

    public function approve(int $leaveId): void
    {
        $leave = Leave::findOrFail($leaveId);
        $this->authorize('approve', $leave);
        try {
            app(LeaveService::class)->approve($leaveId, auth()->id());
            $this->dispatch('leave-updated');
        } catch (\DomainException $e) {
            $this->addError('approve', $e->getMessage());
        }
    }

    public function openRejectModal(int $leaveId): void
    {
        $this->authorize('approve leaves');
        $this->rejectLeaveId = $leaveId;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
        $this->resetValidation();
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->rejectLeaveId = null;
        $this->rejectionReason = '';
    }

    public function submitReject(): void
    {
        $leave = Leave::findOrFail($this->rejectLeaveId);
        $this->authorize('approve', $leave);
        $this->validate(['rejectionReason' => 'required|string|min:3|max:500'], ['rejectionReason.required' => 'Rejection reason is required.']);
        try {
            app(LeaveService::class)->reject($this->rejectLeaveId, $this->rejectionReason, auth()->id());
            $this->closeRejectModal();
            $this->dispatch('leave-updated');
        } catch (\DomainException $e) {
            $this->addError('reject', $e->getMessage());
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render(LeaveService $leaveService): View
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee) {
            return $this->redirect(route('ess.leaves'), navigate: true);
        }

        $this->authorize('view leaves');

        $leaves = $leaveService->listForAdmin([
            'search' => $this->search,
            'status' => $this->status,
        ], 20, $this->getPage());

        return view('livewire.leave.leave-index-page', [
            'leaves' => $leaves,
        ]);
    }
}
