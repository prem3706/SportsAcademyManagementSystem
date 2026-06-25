<?php

namespace App\Http\Controllers;

use App\DataTables\ExpensesDataTable;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ExpensesDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('expense_view'), 403);

        try {
            return $dataTable->render('expenses.index');
        } catch (Exception $e) {
            Log::error('Expense Index Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('expense_create'), 403);

        try {
            $categories = ExpenseCategory::where('status', 1)->get();

            return view('expenses.addExpenseForm', compact('categories'));
        } catch (Exception $e) {
            Log::error('Expense Create Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('expense_create'), 403);

        try {
            $validatedData = $request->validate([
                'expense_category_id' => 'required|exists:expense_categories,id',
                'expense_date' => 'required|date',
                'amount' => 'required|numeric|min:0.01',
                'payment_mode' => 'nullable|string|max:255',
                'reference_no' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            ]);

            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('expense_receipts', 'public');
                $validatedData['receipt'] = 'storage/'.$path;
            }

            Expense::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Expense created successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Expense Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        abort_if(! Auth::user()->can('expense_edit'), 403);

        try {
            $categories = ExpenseCategory::where('status', 1)->get();

            return view('expenses.editExpenseForm', compact('expense', 'categories'));
        } catch (Exception $e) {
            Log::error('Expense Edit Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        abort_if(! Auth::user()->can('expense_edit'), 403);

        try {
            $validatedData = $request->validate([
                'expense_category_id' => 'required|exists:expense_categories,id',
                'expense_date' => 'required|date',
                'amount' => 'required|numeric|min:0.01',
                'payment_mode' => 'nullable|string|max:255',
                'reference_no' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            ]);

            if ($request->hasFile('receipt')) {
                // Delete old file
                if ($expense->receipt) {
                    $oldPath = str_replace('storage/', '', $expense->receipt);
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file('receipt')->store('expense_receipts', 'public');
                $validatedData['receipt'] = 'storage/'.$path;
            }

            $expense->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Expense Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        abort_if(! Auth::user()->can('expense_delete'), 403);

        try {
            // Delete receipt file if exists
            if ($expense->receipt) {
                $oldPath = str_replace('storage/', '', $expense->receipt);
                Storage::disk('public')->delete($oldPath);
            }

            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Expense Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Bulk delete resources.
     */
    public function bulkDelete(Request $request)
    {
        abort_if(! Auth::user()->can('expense_delete'), 403);

        try {
            $ids = $request->input('select', []);

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $expenses = Expense::whereIn('id', $ids)->get();

                foreach ($expenses as $expense) {
                    if ($expense->receipt) {
                        $oldPath = str_replace('storage/', '', $expense->receipt);
                        Storage::disk('public')->delete($oldPath);
                    }
                    $expense->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => count($ids).' Expenses deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No valid Expenses selected for deletion.',
            ], 422);
        } catch (Exception $e) {
            Log::error('Expense Bulk Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}
