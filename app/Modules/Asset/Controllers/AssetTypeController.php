<?php

namespace App\Modules\Asset\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Asset\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage asset types');
        $assetTypes = AssetType::ordered()->paginate(20);
        return view('asset.types.index', compact('assetTypes'));
    }

    public function create()
    {
        $this->authorize('manage asset types');
        return view('asset.types.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage asset types');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|alpha_dash|unique:asset_types,slug',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        AssetType::create($validated);
        return redirect()->route('asset-types.index')->with('success', 'Asset type created.');
    }

    public function edit(AssetType $asset_type)
    {
        $this->authorize('manage asset types');
        return view('asset.types.edit', compact('asset_type'));
    }

    public function update(Request $request, AssetType $asset_type)
    {
        $this->authorize('manage asset types');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|alpha_dash|unique:asset_types,slug,' . $asset_type->id,
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $asset_type->update($validated);
        return redirect()->route('asset-types.index')->with('success', 'Asset type updated.');
    }

    public function destroy(AssetType $asset_type)
    {
        $this->authorize('manage asset types');
        if ($asset_type->assets()->exists()) {
            return redirect()->route('asset-types.index')->with('error', 'Cannot delete: assets exist with this type.');
        }
        $asset_type->delete();
        return redirect()->route('asset-types.index')->with('success', 'Asset type deleted.');
    }
}
