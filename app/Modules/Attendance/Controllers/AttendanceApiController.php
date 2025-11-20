<?php

namespace App\Modules\Attendance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'method' => 'required|in:web,gps,biometric,manual',
        ]);

        $attendance = Attendance::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'employee_id' => $validated['employee_id'],
            'date' => today(),
            'check_in_time' => now(),
            'check_in_method' => $validated['method'],
            'check_in_latitude' => $validated['latitude'] ?? null,
            'check_in_longitude' => $validated['longitude'] ?? null,
            'status' => 'present',
            'created_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Check-in successful', 'data' => $attendance], 201);
    }

    public function checkOut(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'method' => 'required|in:web,gps,biometric,manual',
        ]);

        $attendance = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'No check-in record found'], 404);
        }

        $attendance->update([
            'check_out_time' => now(),
            'check_out_method' => $validated['method'],
            'check_out_latitude' => $validated['latitude'] ?? null,
            'check_out_longitude' => $validated['longitude'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Check-out successful', 'data' => $attendance]);
    }
}

