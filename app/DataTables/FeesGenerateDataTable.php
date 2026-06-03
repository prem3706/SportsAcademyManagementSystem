<?php

namespace App\DataTables;

use App\Models\FeesGenerate;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeesGenerateDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('month_year', function ($row) {

                return $row->month.' - '.$row->year;
            })

            ->editColumn('created_at', function ($row) {

                return $row->created_at->format('d M Y');
            })
            ->editColumn('status', function ($row) {

                if ($row->status == 'paid') {

                    return '<span class="badge bg-success">
                                Paid
                            </span>';
                }

                if ($row->status == 'partial') {

                    return '<span class="badge bg-warning text-dark">
                                Partial
                            </span>';
                }

                return '<span class="badge bg-danger">
                            Unpaid
                        </span>';
            })

            ->addColumn('action', function ($row) {

                return '

                <div class="d-flex justify-content-center gap-2">

                    <a href="'.route('fees-generates.index', [
                    'month' => $row->month,
                    'year' => $row->year,
                ]).'"
                        class="btn btn-sm btn-dark">

                        View Fees

                    </a>

                </div>';
            })

            ->rawColumns(['status', 'action'])

            ->setRowId('id');
    }

    public function query(FeesGenerate $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()

            ->setTableId('datatable')

            ->columns($this->getColumns())

            ->minifiedAjax()

            ->orderBy(0, 'desc');
    }

    public function getColumns(): array
    {
        return [

            Column::make('id'),

            Column::make('month_year')
                ->title('Month / Year'),

            Column::make('status')
                ->title('Status'),

            Column::make('created_at')
                ->title('Generated At'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }
}
