<?php

namespace App\DataTables;

use App\Models\Sport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SportsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<Sport>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('select', function (Sport $Sport) {
                return '<input type="checkbox" class="user-checkbox" name="select[]" value="'.$Sport->id.'">';
            })
            ->addColumn('action', function (Sport $Sport) {
                return '<div class="btn-group align-items-center gap-2">
                <!-- Edit Button -->
                <button type="button"
                    class="btn btn-link p-0 border-0 bi bi-pencil-square mx-2 "
                    id="editSportBtn"
                    data-title="Edit User"
                    data-url="'.route('sports.edit', $Sport->id).'"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasScrolling"
                    aria-controls="offcanvasScrolling">
                </button>

                <!-- Delete Button -->
                <button type="button"
                    class="btn btn-link p-0 border-0 bi bi-trash text-danger "
                    id="deleteSportBtn"
                    data-id="'.$Sport->id.'"
                    data-url="'.route('sports.destroy', $Sport->id).'">
                </button>
            </div>';
            })
            ->rawColumns(['action', 'select']) // Required to render HTML
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Sport>
     */
    public function query(Sport $model): QueryBuilder
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
            ->setTableId('sports-table')
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
            Column::make('description'),
            Column::make('status'),
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
        return 'Sports_'.date('YmdHis');
    }
}
