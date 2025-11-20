<?php

namespace App\Modules\Payroll\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payroll\Models\Payroll;
use Illuminate\Http\Request;

class PayrollApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('view payroll');

        $query = Payroll::with(['employee']);

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        $payrolls = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json($payrolls);
    }

    public function run(Request $request)
    {
        $this->authorize('run payroll');

        // Payroll processing logic will be implemented here
        return response()->json(['message' => 'Payroll processing functionality coming soon']);
    }
}

