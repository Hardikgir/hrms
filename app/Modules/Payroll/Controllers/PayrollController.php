<?php

namespace App\Modules\Payroll\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payroll\Models\Payroll;
use App\Modules\Payroll\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct(
        private PayrollService $payrollService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Payroll::class);

        return view('payroll.index');
    }

    public function show(Payroll $payroll)
    {
        $this->authorize('view', $payroll);

        $payroll->load(['employee.department', 'employee.designation']);
        $auditLogs = $this->payrollService->getAuditLogs($payroll->id);

        return view('payroll.show', compact('payroll', 'auditLogs'));
    }

    public function run()
    {
        $this->authorize('run payroll');

        return view('payroll.run');
    }

    public function lock(Payroll $payroll)
    {
        $this->authorize('update', $payroll);

        try {
            $this->payrollService->lockPayroll($payroll->id, auth()->id());
            return redirect()->route('payroll.show', $payroll)->with('success', 'Payroll locked successfully.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function approve(Payroll $payroll)
    {
        $this->authorize('update', $payroll);

        try {
            $this->payrollService->approvePayroll($payroll->id, auth()->id());
            return redirect()->route('payroll.show', $payroll)->with('success', 'Payroll approved successfully.');
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
