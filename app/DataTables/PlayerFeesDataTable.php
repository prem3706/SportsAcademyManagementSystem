<?php

namespace App\DataTables;

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
                return $fee->player 
                    ? ($fee->player->firstname . ' ' . $fee->player->lastname)
                    : 'Unknown Player';
            })
            ->addColumn('duration', function ($fee) {
                if ($fee->start_date && $fee->end_date) {
                    return $fee->start_date->format('d M Y') . ' to ' . $fee->end_date->format('d M Y');
                }
                return '-';
            })
            ->editColumn('sub_totalamount', function ($fee) {
                return '₹ ' . number_format($fee->sub_totalamount, 2);
            })
            ->editColumn('discount_amount', function ($fee) {
                return '₹ ' . number_format($fee->discount_amount, 2);
            })
            ->editColumn('total_amt', function ($fee) {
                return '₹ ' . number_format($fee->total_amt, 2);
            })
            ->editColumn('payment_type', function ($fee) {
                $type = strtoupper($fee->payment_type);
                if ($fee->payment_type === 'upi' && $fee->upi_id) {
                    return $type . ' (' . $fee->upi_id . ')';
                }
                return $type;
            })
            ->editColumn('img_upi', function ($fee) {
                if ($fee->payment_type === 'upi' && $fee->img_upi) {
                    return '<a href="' . asset($fee->img_upi) . '" target="_blank" class="btn btn-sm btn-outline-dark py-1 px-2 fw-semibold align-items-center gap-1" style="border-radius:8px;">
                                <i class="bi bi-file-earmark-image"></i> View Slip
                            </a>';
                }
                return '-';
            })
            ->editColumn('status', function ($fee) {
                if ($fee->status === 'paid') {
                    return '<span class="badge bg-success px-2 py-1" style="border-radius:12px;">Paid</span>';
                }
                return '<span class="badge bg-warning text-dark px-2 py-1" style="border-radius:12px;">Pending</span>';
            })
            ->addColumn('action', function ($fee) {
                return '
                <div class="d-flex justify-content-center gap-2">
                    <button type="button"
                        class="btn btn-light btn-action text-primary shadow-sm"
                        id="editPlayerFeeBtn"
                        data-title="Edit Player Fee"
                        data-url="' . route('player-fees.edit', $fee->id) . '"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasScrolling">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button"
                        class="btn btn-light btn-action text-danger shadow-sm"
                        id="deletePlayerFeeBtn"
                        data-id="' . $fee->id . '"
                        data-url="' . route('player-fees.destroy', $fee->id) . '">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns([
                'img_upi',
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
        $query = $model->newQuery()->with(['player']);

        // Status Filter
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Payment Type Filter
        if (request()->filled('payment_type')) {
            $query->where('payment_type', request('payment_type'));
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
            Column::make('duration')
                ->title('Coverage Period'),
            Column::make('sub_totalamount')
                ->title('Subtotal'),
            Column::make('discount_amount')
                ->title('Discount'),
            Column::make('total_amt')
                ->title('Total Paid'),
            Column::make('payment_type')
                ->title('Payment Method'),
            Column::make('img_upi')
                ->title('Receipt Slip'),
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
     * Export Filename
     */
    protected function filename(): string
    {
        return 'PlayerFees_' . date('YmdHis');
    }
}
