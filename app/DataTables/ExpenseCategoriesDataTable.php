<?php

namespace App\DataTables;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseCategoriesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<ExpenseCategory>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('select', function (ExpenseCategory $category) {
                return '<input type="checkbox" class="user-checkbox" name="select[]" value="'.$category->id.'">';
            })
            ->editColumn('description', function (ExpenseCategory $category) {
                return Str::limit($category->description, 15);
            })
            ->editColumn('status', function (ExpenseCategory $category) {
                if ($category->status) {
                    return '<span class="badge bg-success">Active</span>';
                }

                return '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function (ExpenseCategory $category) {
                $editBtn = '';
                $deleteBtn = '';

                if (auth()->user()->can('expense_category_edit')) {
                    $editBtn = '
    <!-- Edit Button -->
    <button type="button"
        class="btn btn-light btn-action text-primary shadow-sm"
        id="editExpenseCategoryBtn"
        data-title="Edit Expense Category"
        data-url="'.route('expense-category.edit', $category->id).'"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling">
        <i class="bi bi-pencil-square"></i>
    </button>';
                }

                if (auth()->user()->can('expense_category_delete')) {
                    $deleteBtn = '
    <!-- Delete Button -->
    <button type="button"
        class="btn btn-light btn-action text-danger shadow-sm"
        id="deleteExpenseCategoryBtn"
        data-id="'.$category->id.'"
        data-url="'.route('expense-category.destroy', $category->id).'">
        <i class="bi bi-trash"></i>
    </button>';
                }

                return '<div class="d-flex justify-content-center gap-2">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action', 'select', 'status']) // Required to render HTML
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ExpenseCategory>
     */
    public function query(ExpenseCategory $model): QueryBuilder
    {
        $query = $model->newQuery();

        if (request()->filled('status')) {
            $statusVal = request('status') === 'active' ? 1 : 0;
            $query->where('status', $statusVal);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('datatable')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.status = $("#statusFilter").val();')
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
        $columns = [];

        if (auth()->user()->can('expense_category_delete')) {
            $columns[] = Column::make('select')
                ->title('<input type="checkbox" id="select-all">')
                ->titleAttr('Select All')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false);
        }

        $columns = array_merge($columns, [
            Column::make('id'),
            Column::make('name'),
            Column::make('slug'),
            Column::make('description'),
            Column::make('status')->title('Status'),
        ]);

        if (auth()->user()->can('expense_category_edit') || auth()->user()->can('expense_category_delete')) {
            $columns[] = Column::make('action')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false);
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ExpenseCategories_'.date('YmdHis');
    }
}
