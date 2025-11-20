<?php

namespace App\Modules\Leave\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Leave\Models\Leave;
use Illuminate\Http\Request;

class LeaveApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('view leaves');

        $query = Leave::with(['employee', 'leaveType']);

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $leaves = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json($leaves);
    }

    public function store(Request $request)
    {
        $this->authorize('create leaves');

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['total_days'] = $startDate->diffInDays($endDate) + 1;
        $validated['uuid'] = \Illuminate\Support\Str::uuid();
        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();

        $leave = Leave::create($validated);

        return response()->json(['message' => 'Leave request created', 'data' => $leave], 201);
    }
}

