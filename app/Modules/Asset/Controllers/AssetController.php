<?php

namespace App\Modules\Asset\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Asset\Models\Asset;
use App\Modules\Asset\Services\AssetService;
use App\Modules\Employee\Models\Employee;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function __construct(private AssetService $assetService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Asset::class);
        $employeeId = $request->query('employee_id') ? (int) $request->query('employee_id') : null;
        $status = $request->query('status');
        $assets = $this->assetService->list($employeeId, $status);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        return view('asset.index', compact('assets', 'employees'));
    }

    public function create()
    {
        $this->authorize('create', Asset::class);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        return view('asset.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'serial_number' => 'nullable|string|max:100',
            'asset_tag' => 'nullable|string|max:50',
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'nullable|in:available,assigned,under_maintenance,retired',
            'purchase_date' => 'nullable|date',
            'purchase_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);
        $this->assetService->create($validated, auth()->id());
        return redirect()->route('assets.index')->with('success', 'Asset created.');
    }

    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        return view('asset.edit', compact('asset', 'employees'));
    }

    public function update(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'serial_number' => 'nullable|string|max:100',
            'asset_tag' => 'nullable|string|max:50',
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:available,assigned,under_maintenance,retired',
            'purchase_date' => 'nullable|date',
            'purchase_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);
        $this->assetService->update($asset, $validated);
        return redirect()->route('assets.index')->with('success', 'Asset updated.');
    }

    public function assign(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);
        $validated = $request->validate(['employee_id' => 'required|exists:employees,id']);
        try {
            $this->assetService->assign($asset, (int) $validated['employee_id']);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Asset assigned.');
    }

    public function unassign(Asset $asset)
    {
        $this->authorize('update', $asset);
        $this->assetService->unassign($asset);
        return back()->with('success', 'Asset unassigned.');
    }
}
