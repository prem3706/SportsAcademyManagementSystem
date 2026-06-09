<?php

namespace App\DataTables;

use App\Models\FeesGenerate;
use App\Models\PlayerFee;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PlayerFeesDataTable extends DataTable
{
    /**
     * Build DataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('player', function ($fee) {

                return $fee->user?->firstname.' '.$fee->user?->lastname;
            })

            ->addColumn('sport', function ($fee) {

                return $fee->sport?->name ?? '-';
            })

            ->addColumn('level', function ($fee) {

                return $fee->level?->name ?? '-';
            })

            ->editColumn('amount', function ($fee) {

                return '₹ '.number_format($fee->amount, 2);
            })

            ->editColumn('status', function ($fee) {

                if ($fee->status == 'paid') {

                    return '<span class="badge bg-success">
                                Paid
                            </span>';
                }

                if ($fee->status == 'partial') {

                    return '<span class="badge bg-warning">
                                Partial
                            </span>';
                }

                return '<span class="badge bg-danger">
                            Unpaid
                        </span>';
            })

            ->editColumn('generated_at', function ($fee) {

                return $fee->generated_at
                    ? $fee->generated_at->format('d M Y')
                    : '-';
            })

            ->editColumn('paid_at', function ($fee) {

                return $fee->paid_at
                    ? $fee->paid_at->format('d M Y')
                    : '-';
            })

            ->addColumn('action', function ($fee) {

                return '

                <div class="d-flex justify-content-center gap-2">

                    <button type="button"
                        class="btn btn-light btn-action text-primary shadow-sm "
                        id="editPlayerFeeBtn"
                        data-title="Edit Player Fee"
                        data-url="'.route('player-fees.edit', $fee->id).'"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasScrolling">

                        <i class="bi bi-pencil-square"></i>

                    </button>

                    <button type="button"
                        class="btn btn-light btn-action text-danger shadow-sm"
                        id="deletePlayerFeeBtn"
                        data-id="'.$fee->id.'"
                        data-url="'.route('player-fees.destroy', $fee->id).'">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>
                ';
            })

            ->rawColumns([
                'status',
                'action',
            ])

            ->setRowId('id');
    }

    /**
     * Query
     */
    public function query(PlayerFee $model)
    {
        $query = $model->newQuery()
            ->with([
                'user',
                'sport',
                'level',
            ]);

        // Sport Filter
        if (request()->filled('sport')) {

            $query->where('sport_id', request('sport'));
        }

        // Default year and month values
        // $latestFee = FeesGenerate::orderBy('year', 'desc')
        //     ->orderBy('month', 'desc')
        //     ->first();

        $defaultMonth = now()->month;
        $defaultYear = now()->year;

        // Month Filter
        if (request()->has('month')) {
            if (request()->filled('month')) {
                $query->where('month', request('month'));
            }
        } else {
            $query->where('month', $defaultMonth);
        }

        // Year Filter
        if (request()->has('year')) {
            if (request()->filled('year')) {
                $query->where('year', request('year'));
            }
        } else {
            $query->where('year', $defaultYear);
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

            ->minifiedAjax()

            ->orderBy(1)

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

            Column::make('id')
                ->title('ID'),

            Column::make('player')
                ->title('Player'),

            Column::make('sport')
                ->title('Sport'),

            Column::make('level')
                ->title('Level'),

            Column::make('month')
                ->title('Month'),

            Column::make('year')
                ->title('Year'),

            Column::make('amount')
                ->title('Amount'),

            Column::make('status')
                ->title('Status'),

            Column::make('generated_at')
                ->title('Generated'),

            Column::make('paid_at')
                ->title('Paid Date'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->title('Action'),

        ];
    }

    /**
     * Export Filename
     */
    protected function filename(): string
    {
        return 'PlayerFees_'.date('YmdHis');
    }
}
