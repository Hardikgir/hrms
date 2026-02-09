<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage locations');
        $locations = Location::withCount('employees')->orderBy('name')->paginate(20);
        return view('employee.locations.index', compact('locations'));
    }

    public function create()
    {
        $this->authorize('manage locations');
        return view('employee.locations.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage locations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:locations,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'is_active' => 'boolean',
        ]);
        $validated['uuid'] = (string) Str::uuid();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();
        Location::create($validated);
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        $this->authorize('manage locations');
        return view('employee.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $this->authorize('manage locations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:locations,code,' . $location->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['updated_by'] = auth()->id();
        $location->update($validated);
        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $this->authorize('manage locations');
        if ($location->employees()->exists()) {
            return redirect()->route('locations.index')->with('error', 'Cannot delete: employees are assigned to this location.');
        }
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}
