<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\QueryDataTable;
use Yajra\DataTables\Services\DataTable;

class UnpaidPlayersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable($query): QueryDataTable
    {
        return (new QueryDataTable($query))
            ->addColumn('player', function ($row) {
                return '<span class="fw-semibold text-dark d-block">'.htmlspecialchars($row->player_name).'</span>'.
                       '<span class="text-secondary" style="font-size: 9.5px;">'.htmlspecialchars($row->player_phone ?? 'N/A').'</span>';
            })
            ->addColumn('batch_sport', function ($row) {
                return '<span class="text-dark fw-semibold d-block" style="font-size: 11px;">'.htmlspecialchars($row->batch_name).'</span>'.
                       '<span class="text-muted" style="font-size: 9.5px;">'.htmlspecialchars($row->sport_name).' ('.htmlspecialchars($row->level_name).')</span>';
            })
            ->editColumn('fees', function ($row) {
                return '₹'.number_format($row->fees, 0);
            })
            ->addColumn('action', function ($row) {
                if (auth()->user()->can('fee_create')) {
                    $collectUrl = route('player-fees.create', [
                        'player_id' => $row->player_id,
                        'batch_id' => $row->batch_id,
                        'month' => request('unpaid_month', now()->month),
                        'year' => request('unpaid_year', now()->year),
                    ]);

                    return '
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm fw-bold collect-fee-btn py-0.5 px-2"
                                style="font-size: 10.5px; border-radius: 6px;"
                                data-url="'.$collectUrl.'"
                                data-title="Collect Player Fee"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasScrolling"
                                aria-controls="offcanvasScrolling">
                            Collect Fee
                        </button>
                    </div>
                    ';
                }
                return '-';
            })
            ->rawColumns(['player', 'batch_sport', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        $unpaid_month = intval(request('unpaid_month', now()->month));
        $unpaid_year = intval(request('unpaid_year', now()->year));

        $targetDate = Carbon::createFromDate($unpaid_year, $unpaid_month, 1);
        $startOfMonth = $targetDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $targetDate->copy()->endOfMonth()->toDateString();

        return DB::table('batch_player')
            ->join('users', 'batch_player.player_id', '=', 'users.id')
            ->join('batches', 'batch_player.batch_id', '=', 'batches.id')
            ->join('sports_levels', function ($join) {
                $join->on('batches.sport_id', '=', 'sports_levels.sport_id')
                    ->on('batches.level_id', '=', 'sports_levels.level_id');
            })
            ->join('sports', 'batches.sport_id', '=', 'sports.id')
            ->join('levels', 'batches.level_id', '=', 'levels.id')
            ->where('users.role', 'player')
            ->where('users.status', 'active')
            ->where('batches.status', 'active')
            ->where(function ($q) use ($endOfMonth) {
                $q->whereNull('batch_player.joined_at')
                    ->orWhere('batch_player.joined_at', '<=', $endOfMonth);
            })
            ->whereNotExists(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                $subQuery->select(DB::raw(1))
                    ->from('player_fees')
                    ->whereColumn('player_fees.player_id', '=', 'batch_player.player_id')
                    ->whereColumn('player_fees.batch_id', '=', 'batch_player.batch_id')
                    ->where('player_fees.status', '=', 'paid')
                    ->where('player_fees.start_date', '<=', $endOfMonth)
                    ->where('player_fees.end_date', '>=', $startOfMonth);
            })
            ->select([
                'users.id as player_id',
                DB::raw("CONCAT(users.firstname, ' ', users.lastname) as player_name"),
                'users.phone as player_phone',
                'batches.id as batch_id',
                'batches.name as batch_name',
                'sports.name as sport_name',
                'levels.name as level_name',
                'sports_levels.fees as fees',
            ]);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('datatable')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.unpaid_month = $("#unpaidMonthFilter").val(); data.unpaid_year = $("#unpaidYearFilter").val();')
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'pageLength' => 5,
                'lengthChange' => false,
                'searching' => true,
                'info' => false,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search players...',
                ],
                'dom' => '<"d-flex justify-content-between align-items-center gap-2 mb-3"f>t<"d-flex justify-content-center mt-3"p>',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('player')->title('Player')->name('users.firstname')->addClass('align-middle'),
            Column::make('batch_sport')->title('Batch / Sport')->name('batches.name')->addClass('align-middle'),
            Column::make('fees')->title('Monthly Fee')->name('sports_levels.fees')->addClass('align-middle'),
        ];

        if (auth()->user()->can('fee_create')) {
            $columns[] = Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-center align-middle');
        }

        return $columns;
    }
}
