<?php

namespace App\DataTables;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpensesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<Expense>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('select', function (Expense $expense) {
                return '<input type="checkbox" class="user-checkbox" name="select[]" value="'.$expense->id.'">';
            })
            ->editColumn('category', function (Expense $expense) {
                return $expense->category ? $expense->category->name : '-';
            })
            ->editColumn('expense_date', function (Expense $expense) {
                return $expense->expense_date ? $expense->expense_date->format('d M Y') : '-';
            })
            ->editColumn('amount', function (Expense $expense) {
                return '₹' . number_format($expense->amount, 2);
            })
            ->editColumn('payment_mode', function (Expense $expense) {
                return $expense->payment_mode ? ucfirst($expense->payment_mode) : '-';
            })
            ->editColumn('receipt', function (Expense $expense) {
                if ($expense->receipt) {
                    return '<a href="'.asset($expense->receipt).'" target="_blank" class="btn btn-link btn-sm text-decoration-none px-0 fw-semibold">
                        <i class="bi bi-file-earmark-text me-1"></i>View File
                    </a>';
                }
                return '<span class="text-secondary small">No File</span>';
            })
            ->addColumn('action', function (Expense $expense) {
                return '
<div class="d-flex justify-content-center gap-2">

    <!-- Edit Button -->
    <button type="button"
        class="btn btn-light btn-action text-primary shadow-sm"
        id="editExpenseBtn"
        data-title="Edit Expense"
        data-url="'.route('expenses.edit', $expense->id).'"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling">

        <i class="bi bi-pencil-square"></i>

    </button>

    <!-- Delete Button -->
    <button type="button"
        class="btn btn-light btn-action text-danger shadow-sm"
        id="deleteExpenseBtn"
        data-id="'.$expense->id.'"
        data-url="'.route('expenses.destroy', $expense->id).'">

        <i class="bi bi-trash"></i>

    </button>

</div>';
            })
            ->rawColumns(['action', 'select', 'receipt']) // Required to render HTML
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Expense>
     */
    public function query(Expense $model): QueryBuilder
    {
        // Eager load category relation
        return $model->newQuery()->with('category');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('datatable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('select')
                ->title('<input type="checkbox" id="select-all">')
                ->titleAttr('Select All')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false),
            Column::make('id'),
            Column::make('category')->title('Category')->orderable(false),
            Column::make('expense_date')->title('Date'),
            Column::make('amount')->title('Amount'),
            Column::make('payment_mode')->title('Payment Mode'),
            Column::make('reference_no')->title('Ref No'),
            Column::make('receipt')->title('Receipt'),
            Column::make('action')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Expenses_'.date('YmdHis');
    }
}
