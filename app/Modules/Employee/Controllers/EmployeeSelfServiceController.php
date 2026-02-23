<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Attendance\Models\Attendance;
use App\Modules\Leave\Models\Leave;
use App\Modules\Payroll\Models\Payroll;
use App\Modules\Performance\Services\PerformanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeSelfServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Employee Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        // Get today's attendance
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        // Get pending leaves
        $pendingLeaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->count();

        // Get recent leaves
        $recentLeaves = Leave::where('employee_id', $employee->id)
            ->latest()
            ->limit(5)
            ->get();

        // Get recent attendance
        $recentAttendance = Attendance::where('employee_id', $employee->id)
            ->latest('date')
            ->limit(10)
            ->get();

        // Get latest payslip
        $latestPayslip = Payroll::where('employee_id', $employee->id)
            ->latest()
            ->first();

        return view('employee.ess.dashboard', compact(
            'employee',
            'todayAttendance',
            'pendingLeaves',
            'recentLeaves',
            'recentAttendance',
            'latestPayslip'
        ));
    }

    /**
     * Employee Profile View
     */
    public function profile()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $employee->load(['department', 'designation', 'location', 'manager']);

        return view('employee.ess.profile', compact('employee'));
    }

    /**
     * Employee Profile Edit
     */
    public function editProfile()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        return view('employee.ess.edit-profile', compact('employee'));
    }

    /**
     * Update Employee Profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:255',
        ]);

        $employee->update($validated);

        return redirect()->route('ess.profile')->with('success', __('messages.profile_updated'));
    }

    /**
     * Employee Tasks
     */
    public function tasks()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $tasks = \App\Modules\Employee\Models\EmployeeTask::forEmployee($employee->id)
            ->visibleToEmployee()
            ->orderBy('due_date')
            ->get();

        return view('employee.ess.tasks', compact('employee', 'tasks'));
    }

    /**
     * Mark a task as completed
     */
    public function completeTask(\App\Modules\Employee\Models\EmployeeTask $task)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        // Verify task belongs to employee
        if ($task->employee_id && $task->employee_id !== $employee->id) {
            abort(403, __('messages.unauthorized_access_task'));
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', __('messages.task_marked_completed'));
    }

    /**
     * Onboarding documents – list required docs and upload form
     */
    public function onboardingDocuments()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $requiredDocs = [
            ['key' => 'aadhar', 'label' => 'Aadhar Card', 'description' => 'Front and back copy'],
            ['key' => 'pan', 'label' => 'PAN Card', 'description' => 'Clear copy'],
            ['key' => 'bank_passbook', 'label' => 'Bank Passbook / Cancelled cheque', 'description' => 'For salary credit'],
            ['key' => 'photo', 'label' => 'Passport size photo', 'description' => 'Recent photograph'],
        ];

        return view('employee.ess.onboarding-documents', compact('employee', 'requiredDocs'));
    }

    /**
     * Submit onboarding documents (file upload + document details)
     */
    public function submitOnboardingDocuments(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $rules = [
            'document_type' => 'required|in:aadhar,pan,bank_passbook,photo',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        $type = $request->input('document_type');
        if ($type === 'aadhar') {
            $rules['aadhar_number'] = 'required|digits:12';
        } elseif ($type === 'pan') {
            $rules['pan_number'] = 'required|string|size:10|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/';
        } elseif ($type === 'bank_passbook') {
            $rules['bank_account_number'] = 'required|string|max:50';
            $rules['bank_ifsc'] = 'required|string|size:11|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/';
            $rules['bank_name'] = 'nullable|string|max:255';
            $rules['bank_branch'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $file = $request->file('document');
        $path = $file->store(
            'onboarding/' . $employee->id,
            'local'
        );

        \App\Models\Document::create([
            'employee_id' => $employee->id,
            'document_type' => $request->document_type,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'disk' => 'local',
        ]);

        // Update employee KYC/bank details from form
        if ($type === 'aadhar' && !empty($validated['aadhar_number'])) {
            $employee->update(['aadhar_number' => $validated['aadhar_number']]);
        } elseif ($type === 'pan' && !empty($validated['pan_number'])) {
            $employee->update(['pan_number' => strtoupper($validated['pan_number'])]);
        } elseif ($type === 'bank_passbook') {
            $employee->update([
                'bank_account_number' => $validated['bank_account_number'],
                'bank_ifsc' => $validated['bank_ifsc'],
                'bank_name' => $validated['bank_name'] ?? $employee->bank_name,
                'bank_branch' => $validated['bank_branch'] ?? $employee->bank_branch,
            ]);
        }

        return redirect()->route('ess.onboarding-documents')
            ->with('success', __('messages.document_uploaded'));
    }

    /**
     * Training session – details and confirm attendance
     */
    public function trainingSession()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $trainingTask = \App\Modules\Employee\Models\EmployeeTask::forEmployee($employee->id)
            ->where('action_route', 'ess.training-session')
            ->visibleToEmployee()
            ->first();

        // Placeholder training details (could come from a training_sessions table later)
        $training = [
            'title' => 'Mandatory onboarding training',
            'description' => 'Company policies, code of conduct, and systems overview.',
            'scheduled_at' => now()->addDays(2)->setTime(10, 0, 0),
            'duration' => '2 hours',
            'mode' => 'In-person',
            'venue' => 'Conference Room A, Head Office',
            'agenda' => [
                'Company overview and values',
                'HR policies and leave system',
                'Attendance and payroll (ESS)',
                'IT systems and security',
            ],
            'contact' => 'HR Team (hr@company.com) for any queries.',
        ];

        return view('employee.ess.training-session', compact('employee', 'training', 'trainingTask'));
    }

    /**
     * Confirm training attendance – marks the training-session task as completed.
     */
    public function confirmTrainingAttendance(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $task = \App\Modules\Employee\Models\EmployeeTask::forEmployee($employee->id)
            ->where('action_route', 'ess.training-session')
            ->whereIn('status', ['pending', 'in_progress'])
            ->first();

        if (!$task) {
            return redirect()->route('ess.training-session')->with('error', __('messages.no_pending_training'));
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('ess.tasks')->with('success', __('messages.training_confirmed'));
    }

    /**
     * Employee Attendance View
     */
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $query = Attendance::where('employee_id', $employee->id)->latest('date');

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->paginate(30);

        // Get today's attendance
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        return view('employee.ess.attendance', compact('employee', 'attendances', 'todayAttendance'));
    }

    /**
     * Employee Leaves View
     */
    public function leaves(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $query = Leave::where('employee_id', $employee->id)->with('leaveType')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->paginate(20);

        $pendingCount = Leave::where('employee_id', $employee->id)->where('status', 'pending')->count();
        $approvedCount = Leave::where('employee_id', $employee->id)->where('status', 'approved')->count();
        $rejectedCount = Leave::where('employee_id', $employee->id)->where('status', 'rejected')->count();

        return view('employee.ess.leaves', compact('employee', 'leaves', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Employee Payslips View
     */
    public function payslips(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $query = Payroll::where('employee_id', $employee->id)->latest();

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $payrolls = $query->paginate(12);

        return view('employee.ess.payslips', compact('employee', 'payrolls'));
    }

    /**
     * View Single Payslip
     */
    public function viewPayslip(Payroll $payroll)
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure employee can only view their own payslip
        if (!$employee || $payroll->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $payroll->load(['employee.department', 'employee.designation']);

        return view('employee.ess.payslip-show', compact('payroll'));
    }

    /**
     * ESS – My Goals (KRA/OKR)
     */
    public function goals()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $performanceService = app(\App\Modules\Performance\Services\PerformanceService::class);
        $goals = $performanceService->listGoals($employee->id, null, null);
        return view('employee.ess.goals', compact('employee', 'goals'));
    }

    /**
     * ESS – My Reviews & Reviews to complete (as manager)
     */
    public function reviews()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }

        $performanceService = app(\App\Modules\Performance\Services\PerformanceService::class);
        $myReviews = $performanceService->getReviewsForEmployee($employee->id);
        $reviewsToComplete = $performanceService->getReviewsToCompleteForUser($user->id);
        return view('employee.ess.reviews', compact('employee', 'myReviews', 'reviewsToComplete'));
    }

    public function expenses()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }
        $expenseService = app(\App\Modules\Expense\Services\ExpenseService::class);
        $expenses = $expenseService->list($employee->id, null);
        return view('employee.ess.expenses', compact('employee', 'expenses'));
    }

    public function training()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }
        $trainingService = app(\App\Modules\Training\Services\TrainingService::class);
        $assignments = $trainingService->getAssignmentsForEmployee($employee->id);
        return view('employee.ess.training', compact('employee', 'assignments'));
    }

    public function roster()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }
        $shiftService = app(\App\Modules\Shift\Services\ShiftService::class);
        $weekStart = request()->query('week') ? \Carbon\Carbon::parse(request()->query('week'))->startOfWeek() : now()->startOfWeek();
        $roster = $shiftService->getRoster($weekStart->copy(), $weekStart->copy()->endOfWeek(), $employee->id);
        return view('employee.ess.roster', compact('employee', 'roster', 'weekStart'));
    }

    public function assets()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }
        $assetService = app(\App\Modules\Asset\Services\AssetService::class);
        $assets = $assetService->list($employee->id, null);
        return view('employee.ess.assets', compact('employee', 'assets'));
    }

    public function requestAssetReturn(\App\Modules\Asset\Models\Asset $asset)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }
        if ($asset->employee_id != $employee->id) {
            abort(403, __('messages.asset_not_assigned'));
        }
        $assetService = app(\App\Modules\Asset\Services\AssetService::class);
        try {
            $assetService->requestReturn($asset, $employee->id);
        } catch (\DomainException $e) {
            return redirect()->route('ess.assets')->with('error', $e->getMessage());
        }
        return redirect()->route('ess.assets')->with('success', __('messages.return_request_submitted'));
    }

    public function travel()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }
        $travelService = app(\App\Modules\Travel\Services\TravelService::class);
        $requests = $travelService->list($employee->id, null);
        return view('employee.ess.travel', compact('employee', 'requests'));
    }

    public function exit()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', __('messages.employee_record_not_found'));
        }
        $exitService = app(\App\Modules\Exit\Services\ExitService::class);
        $exits = $exitService->list($employee->id, null);
        return view('employee.ess.exit', compact('employee', 'exits'));
    }
}

