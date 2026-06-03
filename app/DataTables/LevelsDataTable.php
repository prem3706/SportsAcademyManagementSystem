<?php

namespace App\DataTables;

use App\Models\Level;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LevelsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<Level>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('select', function (Level $level) {
                return '<input type="checkbox" class="user-checkbox" name="select[]" value="'.$level->id.'">';
            })
            ->addColumn('action', function (Level $level) {

                return '
<div class="d-flex justify-content-center gap-2">

    <!-- Edit Button -->
    <button type="button"
        class="btn btn-light btn-action text-primary shadow-sm "
        id="editLevelBtn"
        data-title="Edit Level"
        data-url="'.route('levels.edit', $level->id).'"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling">

        <i class="bi bi-pencil-square"></i>

    </button>

    <!-- Delete Button -->
    <button type="button"
        class="btn btn-light btn-action text-danger shadow-sm"
        id="deleteLevelBtn"
        data-id="'.$level->id.'"
        data-url="'.route('levels.destroy', $level->id).'">

        <i class="bi bi-trash"></i>

    </button>

</div>';
            })
            // Status Badge
            ->editColumn('status', function ($level) {

                if ($level->status == 'active') {

                    return '<span class="badge bg-success">
                                Active
                            </span>';
                }

                return '<span class="badge bg-danger">
                            Inactive
                        </span>';
            })
            ->rawColumns(['action', 'select', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Level>
     */
    public function query(Level $model): QueryBuilder
    {
        $query = $model->newQuery();

        if (request()->filled('status')) {

            $query->where('status', request('status'));
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
            Column::make('name'),
            Column::make('slug'),
            Column::make('status')->title('Status'),
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
        return 'Levels_'.date('YmdHis');
    }
}
