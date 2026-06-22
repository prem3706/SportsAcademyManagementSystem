<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseCategoriesDataTable;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ExpenseCategoriesDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('expense_category_view'), 403);

        return $dataTable->render('expenseCategories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('expense_category_create'), 403);

        return view('expenseCategories.addExpenseCategoryForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('expense_category_create'), 403);

        $request->merge([
            'slug' => Str::slug($request->input('name')),
        ]);

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:expense_categories,slug',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:1,0',
        ], [
            'slug.unique' => 'This category name has already been taken.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('slug')) {
                $errors->add('name', $errors->first('slug'));
            }

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        ExpenseCategory::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Expense Category created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(! Auth::user()->can('expense_category_view'), 403);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        abort_if(! Auth::user()->can('expense_category_edit'), 403);

        return view('expenseCategories.editExpenseCategoryForm', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        abort_if(! Auth::user()->can('expense_category_edit'), 403);

        $request->merge([
            'slug' => Str::slug($request->input('name')),
        ]);

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:expense_categories,slug,' . $expenseCategory->id,
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:1,0',
        ], [
            'slug.unique' => 'This category name has already been taken.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('slug')) {
                $errors->add('name', $errors->first('slug'));
            }

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $expenseCategory->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Expense Category updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        abort_if(! Auth::user()->can('expense_category_delete'), 403);

        $expenseCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense Category deleted successfully.',
        ]);
    }

    /**
     * Bulk delete resources.
     */
    public function bulkDelete(Request $request)
    {
        abort_if(! Auth::user()->can('expense_category_delete'), 403);

        $ids = $request->input('select', []);

        if (! is_array($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (count($ids) > 0) {
            $deletedCount = ExpenseCategory::destroy($ids);

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' Expense Categories deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid Expense Categories selected for deletion.',
        ], 422);
    }

    /**
     * Bulk update status.
     */
    public function bulkUpdate(Request $request)
    {
        abort_if(! Auth::user()->can('expense_category_edit'), 403);

        $validated = $request->validate([
            'select' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        $ids = $request->input('select', []);

        $status = $request->input('status') === 'active' ? 1 : 0;

        if (! is_array($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (count($ids) > 0) {
            $updatedCount = ExpenseCategory::whereIn('id', $ids)
                ->update(['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => $updatedCount . ' Expense Categories updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid Expense Categories selected for update.',
        ], 422);
    }
}
