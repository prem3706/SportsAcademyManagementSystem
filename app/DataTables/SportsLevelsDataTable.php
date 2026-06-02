<?php

namespace App\DataTables;

use App\Models\Sport;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SportsLevelsDataTable extends DataTable
{
    /** * Build DataTable class. */
    public function dataTable($query)
    {
        return
        datatables()->eloquent($query)
            ->addColumn('levels_fees', function ($sport) {

                $html = '<div class="d-flex flex-wrap gap-2">';

                foreach ($sport->levels as $level) {

                    $html .= '

        <span class="badge bg-white text-dark border px-3 py-2 fw-normal">

            '.$level->name.'  -
            <span class="text-primary fw-bold ms-1">

                ₹ '.number_format($level->pivot->fees, 2).'

            </span>

        </span>

        ';
                }

                $html .= '</div>';

                return $html;
            })
            ->addColumn('action', function ($sport) {
                return '
<div class="d-flex justify-content-center gap-2">

    <!-- Edit Button -->
    <button type="button"
        class="btn btn-light btn-action text-primary shadow-sm "
        id="editSportsLevelsBtn"
        data-title="Edit Sports Levels"
        data-url="'.route('sport-levels.edit', $sport->id).'"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling">

        <i class="bi bi-pencil-square"></i>

    </button>

    <!-- Delete Button -->
    <button type="button"
        class="btn btn-light btn-action text-danger shadow-sm"
        id="deleteSportBtn"
        data-id="'.$sport->id.'"
        data-url="'.route('sports.destroy', $sport->id).'">

        <i class="bi bi-trash"></i>

    </button>

</div>';
            })->rawColumns(['levels_fees', 'action']);
    }

    /** * Get query source. */
    public function query(Sport $model)
    {
        return $model->newQuery()->with('levels')->whereHas('levels')->orderBy('created_at', 'desc');
    }

    /** * Optional HTML builder. */
    public function html()
    {
        return
        $this->builder()
            ->setTableId('datatable')
            ->columns($this->getColumns())
            ->minifiedAjax()->dom("<'row align-items-center mb-3'<'col-md-6'l><'col-md-6 text-end'f>>".'tr'."<'row mt-3'<'col-md-5'i><'col-md-7'p>>")->orderBy(0)->responsive(true)->autoWidth(false)->addTableClass('table table-hover align-middle');
    }

    /** * Get columns. */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::make('name')->title('Sport'),
            Column::computed('levels_fees')
                ->title('Levels & Fees')
                ->searchable(false)
                ->orderable(false),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(140)
                ->addClass('text-center'),
        ];
    }

    /** * Filename for export. */
    protected function filename(): string
    {
        return 'SportsLevels_'.date('YmdHis');
    }
}
