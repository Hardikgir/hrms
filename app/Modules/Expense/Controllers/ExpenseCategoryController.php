<?php

namespace App\Modules\Expense\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Expense\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage expense categories');
        $categories = ExpenseCategory::ordered()->paginate(20);
        return view('expense.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('manage expense categories');
        return view('expense.categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage expense categories');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|alpha_dash|unique:expense_categories,slug',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        ExpenseCategory::create($validated);
        return redirect()->route('expense-categories.index')->with('success', 'Expense category created.');
    }

    public function edit(ExpenseCategory $expense_category)
    {
        $this->authorize('manage expense categories');
        return view('expense.categories.edit', compact('expense_category'));
    }

    public function update(Request $request, ExpenseCategory $expense_category)
    {
        $this->authorize('manage expense categories');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|alpha_dash|unique:expense_categories,slug,' . $expense_category->id,
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $expense_category->update($validated);
        return redirect()->route('expense-categories.index')->with('success', 'Expense category updated.');
    }

    public function destroy(ExpenseCategory $expense_category)
    {
        $this->authorize('manage expense categories');
        $expense_category->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Expense category deleted.');
    }
}
