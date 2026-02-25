<?php

namespace App\Modules\Travel\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Travel\Models\TravelRequest;
use App\Modules\Travel\Services\TravelService;
use Illuminate\Http\Request;

class TravelRequestController extends Controller
{
    public function __construct(private TravelService $travelService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', TravelRequest::class);
        $user = auth()->user();
        $employeeId = $user->employee ? $user->employee->id : ($request->query('employee_id') ? (int) $request->query('employee_id') : null);
        $status = $request->query('status');
        $requests = $this->travelService->list($employeeId, $status);
        $employees = $user->can('view travel') ? Employee::where('is_active', true)->orderBy('first_name')->get() : collect();
        return view('travel.index', compact('requests', 'employees'));
    }

    public function create()
    {
        $this->authorize('create', TravelRequest::class);
        $employee = auth()->user()->employee;
        if (!$employee) {
            abort(403, 'Employee record required.');
        }
        if (session('portal') === \App\Services\PortalService::PORTAL_EMPLOYEE && !request()->routeIs('ess.travel.*')) {
            return redirect()->route('ess.travel.create');
        }
        if (request()->routeIs('ess.travel.*')) {
            return view('employee.ess.travel-create', compact('employee'));
        }
        return view('travel.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', TravelRequest::class);
        $employee = auth()->user()->employee;
        if (!$employee) {
            abort(403, 'Employee record required.');
        }
        $validated = $request->validate([
            'purpose' => 'required|string|max:500',
            'destination' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'estimated_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
        ]);
        $this->travelService->submit(
            $employee->id,
            $validated['purpose'],
            $validated['start_date'],
            $validated['end_date'],
            $validated['destination'] ?? null,
            isset($validated['estimated_amount']) ? (float) $validated['estimated_amount'] : null,
            $validated['notes'] ?? null,
            auth()->id()
        );
        $redirect = session('portal') === \App\Services\PortalService::PORTAL_EMPLOYEE ? route('ess.travel') : route('travel.index');
        return redirect($redirect)->with('success', __('messages.travel_request_submitted'));
    }

    public function show(TravelRequest $travel)
    {
        $this->authorize('view', $travel);
        $travel->load(['employee', 'approvedBy']);
        $user = auth()->user();
        if (session('portal') === \App\Services\PortalService::PORTAL_EMPLOYEE && $travel->employee_id === $user->employee->id && !request()->routeIs('ess.travel.*')) {
            return redirect()->route('ess.travel.show', $travel);
        }
        if (request()->routeIs('ess.travel.*')) {
            return view('employee.ess.travel-show', compact('travel'));
        }
        return view('travel.show', compact('travel'));
    }

    public function approve(TravelRequest $travel)
    {
        $this->authorize('approve', $travel);
        try {
            $this->travelService->approve($travel->id, auth()->id());
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', __('messages.request_approved_success'));
    }

    public function reject(Request $request, TravelRequest $travel)
    {
        $this->authorize('approve', $travel);
        $validated = $request->validate(['reason' => 'required|string|max:1000']);
        try {
            $this->travelService->reject($travel->id, $validated['reason'], auth()->id());
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', __('messages.request_rejected_success'));
    }

    public function complete(Request $request, TravelRequest $travel)
    {
        $this->authorize('approve', $travel);
        $validated = $request->validate(['actual_amount' => 'nullable|numeric|min:0']);
        try {
            $this->travelService->markCompleted($travel->id, isset($validated['actual_amount']) ? (float) $validated['actual_amount'] : null);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', __('messages.marked_completed_success'));
    }
}
