<?php

namespace App\DataTables;

use App\Models\PlayerFee;
use Carbon\Carbon;
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
                    ? ($fee->player->firstname.' '.$fee->player->lastname)
                    : 'Unknown Player';
            })
            ->addColumn('batch', function ($fee) {
                return $fee->batch
                    ? $fee->batch->name
                    : 'N/A';
            })
            ->addColumn('duration', function ($fee) {
                if ($fee->start_date && $fee->end_date) {
                    return $fee->start_date->format('M Y').
                        '<br><span class="small text-muted">to '.
                        $fee->end_date->format('M Y').
                        '</span>';
                }

                return '-';
            })

            ->editColumn('sub_totalamount', function ($fee) {
                return '₹'.number_format($fee->sub_totalamount, 0);
            })
            ->editColumn('discount_amount', function ($fee) {
                return '₹'.number_format($fee->discount_amount, 0);
            })
            ->editColumn('penalty_amount', function ($fee) {
                return '₹'.number_format($fee->penalty_amount, 0);
            })
            ->editColumn('total_amt', function ($fee) {
                return '₹'.number_format($fee->total_amt, 0);
            })
            ->editColumn('payment_type', function ($fee) {
                $type = strtoupper($fee->payment_type);
                if ($fee->payment_type === 'upi' && $fee->upi_id) {
                    return $type.'<br><small class="text-muted" style="font-size: 11px;">'.$fee->upi_id.'</small>';
                }

                return $type;
            })
            ->editColumn('img_upi', function ($fee) {
                if ($fee->payment_type === 'upi' && $fee->img_upi) {
                    return '<a href="'.asset($fee->img_upi).'" target="_blank" class="btn btn-sm btn-outline-dark py-1 px-2 fw-semibold align-items-center gap-1" style="border-radius:8px;">
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
                        class="btn btn-light btn-action text-primary shadow-sm edit-fee-btn"
                        data-title="Edit Player Fee"
                        data-url="'.route('player-fees.edit', $fee->id).'"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasScrolling">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button"
                        class="btn btn-light btn-action text-danger shadow-sm delete-fee-btn"
                        data-id="'.$fee->id.'"
                        data-url="'.route('player-fees.destroy', $fee->id).'">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns([
                'payment_type',
                'img_upi',
                'status',
                'action',
                'duration',
            ])
            ->setRowId('id');
    }

    /**
     * Query
     */
    public function query(PlayerFee $model)
    {
        $query = $model->newQuery()->with(['player', 'batch']);

        // Status Filter
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Payment Type Filter
        if (request()->filled('payment_type')) {
            $query->where('payment_type', request('payment_type'));
        }

        // Player Filter
        if (request()->filled('player_id')) {
            $query->where('player_id', request('player_id'));
        }

        // Batch Filter
        if (request()->filled('batch')) {
            $query->where('batch_id', request('batch'));
        }

        // Month and Year Filters (Period-wise search)
        if (request()->filled('month') && request()->filled('year')) {
            $month = request('month');
            $year = request('year');
            $targetStart = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
            $targetEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');

            $query->where('start_date', '<=', $targetEnd)
                ->where('end_date', '>=', $targetStart);
        } elseif (request()->filled('month')) {
            $month = (int) request('month');
            $query->where(function ($q) use ($month) {
                $q->whereRaw('TIMESTAMPDIFF(MONTH, start_date, end_date) >= 11')
                    ->orWhere(function ($sub) use ($month) {
                        $sub->whereRaw('YEAR(start_date) = YEAR(end_date)')
                            ->whereRaw('MONTH(start_date) <= ?', [$month])
                            ->whereRaw('MONTH(end_date) >= ?', [$month]);
                    })
                    ->orWhere(function ($sub) use ($month) {
                        $sub->whereRaw('YEAR(end_date) > YEAR(start_date)')
                            ->where(function ($inner) use ($month) {
                                $inner->whereRaw('MONTH(start_date) <= ?', [$month])
                                    ->orWhereRaw('MONTH(end_date) >= ?', [$month]);
                            });
                    });
            });
        } elseif (request()->filled('year')) {
            $year = request('year');
            $targetStart = Carbon::createFromDate($year, 1, 1)->startOfYear()->format('Y-m-d');
            $targetEnd = Carbon::createFromDate($year, 1, 1)->endOfYear()->format('Y-m-d');

            $query->where('start_date', '<=', $targetEnd)
                ->where('end_date', '>=', $targetStart);
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
            ->minifiedAjax('', 'data.month = $("#monthFilter").val(); data.year = $("#yearFilter").val(); data.status = $("#statusFilter").val(); data.batch = $("#batchFilter").val(); data.payment_type = $("#paymentTypeFilter").val(); data.player_id = $("#playerFilter").val();')
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
            Column::make('batch')
                ->title('Batch')
                ->addClass('text-nowrap'),
            Column::make('duration')
                ->title('Period'),
            Column::make('sub_totalamount')
                ->title('Subtotal')
                ->addClass('text-nowrap'),
            Column::make('discount_amount')
                ->title('Discount')
                ->addClass('text-nowrap'),
            Column::make('penalty_amount')
                ->title('Penalty')
                ->addClass('text-nowrap'),
            Column::make('total_amt')
                ->title('Total')
                ->addClass('text-nowrap'),
            Column::make('payment_type')
                ->title('Method')
                ->addClass('text-nowrap'),
            Column::make('img_upi')
                ->title('Receipt')
                ->addClass('text-nowrap'),
            Column::make('status')
                ->title('Status')
                ->addClass('text-nowrap'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
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
