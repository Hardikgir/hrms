<?php

namespace App\Modules\Expense\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Expense\Models\Expense;
use App\Modules\Expense\Models\ExpenseCategory;
use App\Modules\Expense\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function __construct(private ExpenseService $expenseService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Expense::class);
        $user = auth()->user();
        $employeeId = $user->employee ? $user->employee->id : ($request->query('employee_id') ? (int) $request->query('employee_id') : null);
        $status = $request->query('status');
        $expenses = $this->expenseService->list($employeeId, $status);
        $employees = $user->can('view expenses') ? Employee::where('is_active', true)->orderBy('first_name')->get() : collect();
        return view('expense.index', compact('expenses', 'employees'));
    }

    public function create()
    {
        $this->authorize('create', Expense::class);
        $employee = auth()->user()->employee;
        if (!$employee) {
            abort(403, 'Employee record required.');
        }
        $categories = ExpenseCategory::active()->ordered()->get();
        if ($employee && !request()->routeIs('ess.expenses.*')) {
            return redirect()->route('ess.expenses.create');
        }
        if (request()->routeIs('ess.expenses.*')) {
            return view('employee.ess.expense-create', compact('employee', 'categories'));
        }
        return view('expense.create', compact('employee', 'categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Expense::class);
        $employee = auth()->user()->employee;
        if (!$employee) {
            abort(403, 'Employee record required.');
        }
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'category' => ['required', 'string', 'max:100', Rule::in(ExpenseCategory::activeSlugs())],
            'description' => 'nullable|string|max:2000',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        try {
            $this->expenseService->submit(
                $employee->id,
                (float) $validated['amount'],
                $validated['category'],
                $validated['description'] ?? null,
                $request->file('receipt'),
                auth()->id()
            );
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        $redirect = auth()->user()->employee ? route('ess.expenses') : route('expenses.index');
        return redirect($redirect)->with('success', 'Expense submitted.');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        $expense->load(['employee', 'approvedBy', 'reimbursedBy', 'rejectedBy']);
        $user = auth()->user();
        if ($user->employee && $expense->employee_id === $user->employee->id && !request()->routeIs('ess.expenses.*')) {
            return redirect()->route('ess.expenses.show', $expense);
        }
        if (request()->routeIs('ess.expenses.*')) {
            return view('employee.ess.expense-show', compact('expense'));
        }
        return view('expense.show', compact('expense'));
    }

    public function downloadReceipt(Expense $expense)
    {
        $this->authorize('view', $expense);
        if (!$expense->receipt_path) {
            abort(404, 'No receipt uploaded for this expense.');
        }
        if (!Storage::disk('local')->exists($expense->receipt_path)) {
            abort(404, 'Receipt file not found.');
        }
        $ext = pathinfo($expense->receipt_path, PATHINFO_EXTENSION) ?: 'pdf';
        $downloadName = 'expense-' . $expense->id . '-receipt.' . $ext;
        return Storage::disk('local')->download($expense->receipt_path, $downloadName);
    }

    public function approve(Expense $expense)
    {
        $this->authorize('approve', $expense);
        try {
            $this->expenseService->approve($expense->id, auth()->id());
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Expense approved.');
    }

    public function reject(Request $request, Expense $expense)
    {
        $this->authorize('approve', $expense);
        $validated = $request->validate(['reason' => 'required|string|max:1000']);
        try {
            $this->expenseService->reject($expense->id, $validated['reason'], auth()->id());
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Expense rejected.');
    }

    public function reimburse(Expense $expense)
    {
        $this->authorize('reimburse', $expense);
        try {
            $this->expenseService->markReimbursed($expense->id, auth()->id());
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Marked as reimbursed.');
    }
}
