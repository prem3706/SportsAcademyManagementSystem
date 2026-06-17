<?php

namespace App\DataTables;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BatchesDataTable extends DataTable
{
    /**
     * Build DataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('select', function (Batch $batch) {
                return '<input type="checkbox" class="user-checkbox" name="select[]" value="'.$batch->id.'">';
            })

            // Sport
            ->addColumn('sport', function ($batch) {

                return $batch->sport?->name ?? '-';

            })

            // Level
            ->addColumn('level', function ($batch) {

                return $batch->level?->name ?? '-';

            })

            // Timing
            ->addColumn('timing', function ($batch) {

                return date('h:i A', strtotime($batch->start_time))
                    .' - '.
                    date('h:i A', strtotime($batch->end_time));

            })

            // Coaches Count
            ->addColumn('coaches_count', function ($batch) {

                return $batch->coaches->count();

            })

            // Players Count
            ->addColumn('players_count', function ($batch) {

                return $batch->players->count();

            })

            // Status Badge
            ->editColumn('status', function ($batch) {

                if ($batch->status == 'active') {

                    return '<span class="badge bg-success">
                                Active
                            </span>';
                }

                return '<span class="badge bg-danger">
                            Inactive
                        </span>';
            })

            // Action Buttons
            ->addColumn('action', function ($batch) {

                return '
                        <div class="d-flex justify-content-center gap-2">

                            <button type="button"
                                class="btn btn-light btn-action text-primary shadow-sm"
                                id="editBatchBtn"
                                data-title="Edit Batch"
                                data-url="'.route('batches.edit', $batch->id).'"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasScrolling">

                                <i class="bi bi-pencil-square"></i>

                            </button>

                            <button type="button"
                                class="btn btn-light btn-action text-danger shadow-sm"
                                id="deleteBatchBtn"
                                data-url="'.route('batches.destroy', $batch->id).'">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>';
            })

            ->rawColumns(['status', 'action', 'select']) // Required to render HTML

            ->setRowId('id');
    }

    /**
     * Query
     */
    public function query(Batch $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with([
                'sport',
                'level',
                'coaches',
                'players',
            ]);

        if (request()->filled('status')) {

            $query->where('status', request('status'));
        }

        return $query;
    }

    /**
     * HTML Builder
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
     * Columns
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

            Column::make('id')
                ->title('ID'),

            Column::make('name')
                ->title('Batch Name'),

            Column::make('sport')
                ->title('Sport'),

            Column::make('level')
                ->title('Level'),

            Column::make('capacity')
                ->title('Capacity'),

            Column::make('timing')
                ->title('Batch Timing'),

            Column::make('coaches_count')
                ->title('Coaches'),

            Column::make('players_count')
                ->title('Players'),

            Column::make('status')
                ->title('Status'),

            Column::computed('action')

                ->exportable(false)

                ->printable(false)

                ->width(100)

                ->addClass('text-center')

                ->title('Action'),

        ];
    }

    /**
     * Export File Name
     */
    protected function filename(): string
    {
        return 'Batches_'.date('YmdHis');
    }
}
// can you give some style to look professional and clean? You can use Bootstrap 5 classes to enhance the appearance of the DataTable.
