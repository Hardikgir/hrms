<?php

namespace App\Modules\Training\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Training\Models\TrainingAssignment;
use App\Modules\Training\Models\TrainingCourse;
use App\Modules\Training\Services\TrainingService;
use Illuminate\Http\Request;

class TrainingAssignmentController extends Controller
{
    public function __construct(private TrainingService $trainingService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', TrainingAssignment::class);
        $employeeId = $request->query('employee_id') ? (int) $request->query('employee_id') : null;
        $status = $request->query('status');
        $assignments = $this->trainingService->listAssignments($employeeId, $status);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        return view('training.assignments.index', compact('assignments', 'employees'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', TrainingAssignment::class);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $courses = TrainingCourse::where('is_active', true)->orderBy('name')->get();
        return view('training.assignments.create', compact('employees', 'courses'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', TrainingAssignment::class);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'training_course_id' => 'required|exists:training_courses,id',
            'due_date' => 'nullable|date',
        ]);
        try {
            $this->trainingService->assign(
                (int) $validated['employee_id'],
                (int) $validated['training_course_id'],
                $validated['due_date'] ?? null,
                auth()->id()
            );
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        return redirect()->route('training.assignments.index')->with('success', 'Training assigned.');
    }

    public function complete(TrainingAssignment $training_assignment)
    {
        $this->authorize('update', $training_assignment);
        $validated = request()->validate(['score' => 'nullable|integer|min:0|max:100']);
        $this->trainingService->markCompleted($training_assignment->id, $validated['score'] ?? null);
        return back()->with('success', 'Marked as completed.');
    }
}
