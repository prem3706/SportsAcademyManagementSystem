<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseCategoriesDataTable;
use App\Http\Requests\ExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ExpenseCategoriesDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('expense_category_view'), 403);

        try {
            return $dataTable->render('expenseCategories.index');
        } catch (Exception $e) {
            Log::error('ExpenseCategory Index Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('expense_category_create'), 403);

        try {
            return view('expenseCategories.addExpenseCategoryForm');
        } catch (Exception $e) {
            Log::error('ExpenseCategory Create Form Error: '.$e->getMessage());

            return abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseCategoryRequest $request)
    {
        abort_if(! Auth::user()->can('expense_category_create'), 403);

        try {
            ExpenseCategory::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Expense Category created successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('ExpenseCategory Store Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {

    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        abort_if(! Auth::user()->can('expense_category_edit'), 403);

        try {
            return view('expenseCategories.editExpenseCategoryForm', compact('expenseCategory'));
        } catch (Exception $e) {
            Log::error('ExpenseCategory Edit Form Error: '.$e->getMessage());

            return abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseCategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        abort_if(! Auth::user()->can('expense_category_edit'), 403);

        try {
            $expenseCategory->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Expense Category updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('ExpenseCategory Update Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        abort_if(! Auth::user()->can('expense_category_delete'), 403);

        try {
            $expenseCategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expense Category deleted successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('ExpenseCategory Delete Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        return handleBulkDelete($request, ExpenseCategory::class, 'Expense Categories', 'expense_category_delete');
    }

    public function bulkUpdate(Request $request)
    {
        return handleBulkUpdate($request, ExpenseCategory::class, 'Expense Categories', 'expense_category_edit', function ($status) {
            return $status === 'active' ? 1 : 0;
        });
    }
}
