<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PlayersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<User>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('select', function (User $user) {
                return '<input type="checkbox" class="user-checkbox" name="select[]" value="'.$user->id.'">';
            })
            ->addColumn('action', function (User $user) {
                $actions = '
<div class="d-flex justify-content-center gap-2">

    <!-- Edit Button -->
    <button type="button"
        class="btn btn-light btn-action text-primary shadow-sm"
        id="editUserBtn"
        data-title="Edit Player"
        data-url="'.route('players.edit', $user->id).'"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling">

        <i class="bi bi-pencil-square"></i>

    </button>';

                if (Auth::id() !== $user->id) {
                    $actions .= '

    <!-- Delete Button -->
    <button type="button"
        class="btn btn-light btn-action text-danger shadow-sm"
        id="deleteUserBtn"
        data-id="'.$user->id.'"
        data-url="'.route('players.destroy', $user->id).'">

        <i class="bi bi-trash"></i>

    </button>';
                }

                $actions .= '
</div>';

                return $actions;
            })
            ->editColumn('status', function (User $user) {
                if ($user->status == 'active') {
                    return '<span class="badge bg-success">Active</span>';
                }

                return '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('Name', function (User $user) {
                return $user->firstname.' '.$user->lastname;
            })
            ->addColumn('sport', function (User $user) {
                return $user->playerBatches->map(function ($batch) {
                    return $batch->sport ? $batch->sport->name : '';
                })->filter()->unique()->implode(', ');
            })
            ->addColumn('level', function (User $user) {
                return $user->playerBatches->map(function ($batch) {
                    return $batch->level ? $batch->level->name : '';
                })->filter()->unique()->implode(', ');
            })
            ->addColumn('batch', function (User $user) {
                return $user->playerBatches->pluck('name')->filter()->unique()->implode(', ');
            })
            ->setRowId('id')
            ->rawColumns(['select', 'action', 'status']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->where('role', 'player')
            ->with(['playerBatches.sport', 'playerBatches.level']);

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('sport')) {
            $query->whereHas('playerBatches', function ($q) {
                $q->where('sport_id', request('sport'));
            });
        }

        if (request()->filled('level')) {
            $query->whereHas('playerBatches', function ($q) {
                $q->where('level_id', request('level'));
            });
        }

        if (request()->filled('batch')) {
            $query->whereHas('playerBatches', function ($q) {
                $q->where('id', request('batch'));
            });
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
            Column::make('Name')->data('Name')->name('firstname'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('sport')->title('Sport')->orderable(false)->searchable(false),
            Column::make('level')->title('Level')->orderable(false)->searchable(false),
            Column::make('batch')->title('Batch')->orderable(false)->searchable(false),
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
        return 'Players_'.date('YmdHis');
    }
}
