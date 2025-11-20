<?php

namespace App\Modules\Payroll\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payroll\Models\Payroll;
use App\Modules\Employee\Models\Employee;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->latest()->paginate(20);
        $employees = Employee::where('is_active', true)->get();

        return view('payroll.index', compact('payrolls', 'employees'));
    }

    public function show(Payroll $payroll)
    {
        $this->authorize('view payroll');

        $payroll->load(['employee.department', 'employee.designation']);

        return view('payroll.show', compact('payroll'));
    }

    public function run()
    {
        $this->authorize('run payroll');

        $employees = Employee::where('is_active', true)
            ->where('employment_status', 'active')
            ->get();

        return view('payroll.run', compact('employees'));
    }
}

