<?php

namespace App\Modules\Exit\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Exit\Models\ExitRequest;
use App\Modules\Exit\Services\ExitService;
use Illuminate\Http\Request;

class ExitRequestController extends Controller
{
    public function __construct(private ExitService $exitService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', ExitRequest::class);
        $user = auth()->user();
        $employeeId = $user->employee ? $user->employee->id : ($request->query('employee_id') ? (int) $request->query('employee_id') : null);
        $status = $request->query('status');
        $exits = $this->exitService->list($employeeId, $status);
        $employees = $user->can('view exit') ? Employee::where('is_active', true)->orderBy('first_name')->get() : collect();
        return view('exit.index', compact('exits', 'employees'));
    }

    public function create()
    {
        $this->authorize('create', ExitRequest::class);
        $employee = auth()->user()->employee;
        if (!$employee) {
            abort(403, 'Employee record required.');
        }
        if (session('portal') === \App\Services\PortalService::PORTAL_EMPLOYEE && !request()->routeIs('ess.exit.*')) {
            return redirect()->route('ess.exit.create');
        }
        if (request()->routeIs('ess.exit.*')) {
            return view('employee.ess.exit-create', compact('employee'));
        }
        return view('exit.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', ExitRequest::class);
        $employee = auth()->user()->employee;
        if (!$employee) {
            abort(403, 'Employee record required.');
        }
        $validated = $request->validate([
            'resignation_date' => 'required|date',
            'last_working_date' => 'required|date|after_or_equal:resignation_date',
            'reason' => 'nullable|string|max:100',
            'reason_details' => 'nullable|string|max:2000',
        ]);
        try {
            $this->exitService->submit(
                $employee->id,
                $validated['resignation_date'],
                $validated['last_working_date'],
                $validated['reason'] ?? null,
                $validated['reason_details'] ?? null,
                auth()->id()
            );
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        $redirect = session('portal') === \App\Services\PortalService::PORTAL_EMPLOYEE ? route('ess.exit') : route('exit.index');
        return redirect($redirect)->with('success', __('messages.exit_request_submitted'));
    }

    public function show(ExitRequest $exit)
    {
        $this->authorize('view', $exit);
        $exit->load(['employee', 'approvedBy']);
        return view('exit.show', compact('exit'));
    }

    public function updateStatus(Request $request, ExitRequest $exit)
    {
        $this->authorize('update', $exit);
        $validated = $request->validate([
            'status' => 'required|in:pending,clearance,exit_interview,settlement,completed',
            'exit_interview_notes' => 'nullable|string|max:5000',
        ]);
        $data = ['status' => $validated['status']];
        if (!empty($validated['exit_interview_notes'])) {
            $data['exit_interview_notes'] = $validated['exit_interview_notes'];
        }
        $this->exitService->updateStatus($exit, $validated['status'], $data);
        return back()->with('success', __('messages.status_updated_success'));
    }

    public function updateChecklist(Request $request, ExitRequest $exit)
    {
        $this->authorize('update', $exit);
        $validated = $request->validate([
            'checklist' => 'required|array',
            'checklist.*' => 'boolean',
        ]);
        $this->exitService->updateChecklist($exit, $validated['checklist']);
        return back()->with('success', __('messages.checklist_updated_success'));
    }

    public function completeClearance(ExitRequest $exit)
    {
        $this->authorize('update', $exit);
        $this->exitService->completeClearance($exit);
        return back()->with('success', __('messages.clearance_completed_success'));
    }

    public function recordSettlement(Request $request, ExitRequest $exit)
    {
        $this->authorize('update', $exit);
        $validated = $request->validate([
            'settlement_amount' => 'required|numeric|min:0',
            'settlement_paid_at' => 'nullable|date',
        ]);
        $this->exitService->recordSettlement($exit, (float) $validated['settlement_amount'], $validated['settlement_paid_at'] ?? null);
        return back()->with('success', __('messages.settlement_recorded_success'));
    }
}
