<?php

namespace App\Modules\Training\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Training\Models\TrainingCourse;
use App\Modules\Training\Services\TrainingService;
use Illuminate\Http\Request;

class TrainingCourseController extends Controller
{
    public function __construct(private TrainingService $trainingService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', TrainingCourse::class);
        $type = $request->query('type');
        $courses = $this->trainingService->listCourses($type, false);
        return view('training.courses.index', compact('courses'));
    }

    public function create()
    {
        $this->authorize('create', TrainingCourse::class);
        return view('training.courses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', TrainingCourse::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'duration_hours' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $this->trainingService->createCourse($validated, auth()->id());
        return redirect()->route('training.courses.index')->with('success', __('messages.course_created_success'));
    }

    public function edit(TrainingCourse $course)
    {
        $this->authorize('update', $course);
        return view('training.courses.edit', compact('course'));
    }

    public function update(Request $request, TrainingCourse $course)
    {
        $this->authorize('update', $course);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'duration_hours' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $this->trainingService->updateCourse($course, $validated);
        return redirect()->route('training.courses.index')->with('success', __('messages.course_updated_success'));
    }
}
