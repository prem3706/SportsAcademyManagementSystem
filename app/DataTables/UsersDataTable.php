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

class UsersDataTable extends DataTable
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
                $editBtn = '';
                $deleteBtn = '';

                if (auth()->user()->can('user_edit')) {
                    $editBtn = '
    <!-- Edit Button -->
    <button type="button"
        class="btn btn-light btn-action text-primary shadow-sm"
        id="editUserBtn"
        data-title="Edit User"
        data-url="'.route('users.edit', $user->id).'"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasScrolling">
        <i class="bi bi-pencil-square"></i>
    </button>';
                }

                if (auth()->user()->can('user_delete') && auth()->id() !== $user->id) {
                    $deleteBtn = '
    <!-- Delete Button -->
    <button type="button"
        class="btn btn-light btn-action text-danger shadow-sm"
        id="deleteUserBtn"
        data-id="'.$user->id.'"
        data-url="'.route('users.destroy', $user->id).'">
        <i class="bi bi-trash"></i>
    </button>';
                }

                return '<div class="d-flex justify-content-center gap-2">'.$editBtn.$deleteBtn.'</div>';
            })
            ->editColumn('status', function (User $user) {
                if ($user->status == 'active') {
                    return '<span class="badge bg-success">Active</span>';
                }

                return '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('created_at', function (User $user) {
                return $user->created_at->format('Y-m-d');
            })
            ->editColumn('updated_at', function (User $user) {
                return $user->updated_at->format('Y-m-d');
            })
            ->editColumn('Name', function (User $user) {
                $fullName = $user->firstname.' '.'('.$user->getRoleNames()->first().')';

                if (Auth::id() === $user->id) {
                    $fullName .= ' <span class="badge bg-secondary">You</span>';
                }

                return $fullName;
            })
            ->editColumn('role', function (User $user) {
                return ucfirst($user->getRoleNames()->first() ?? '');
            })
            ->setRowId('id')
            ->rawColumns(['select', 'action', 'Name', 'status']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery();

        if (request()->filled('status')) {

            $query->where('status', request('status'));
        }

        if (request()->filled('role')) {
            $query->role(request('role'));
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
            ->minifiedAjax('', 'data.status = $("#statusFilter").val(); data.role = $("#roleFilter").val();')
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
        $columns = [];

        if (auth()->user()->can('user_delete')) {
            $columns[] = Column::make('select')
                ->title('<input type="checkbox" id="select-all">')
                ->titleAttr('Select All')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false);
        }

        $columns = array_merge($columns, [
            Column::make('id'),
            Column::make('Name')->data('Name')->name('firstname'),
            Column::make('phone'),
            Column::make('gender'),
            Column::make('status')->title('Status'),
            Column::make('role')->orderable(false)->searchable(false),
        ]);

        if (auth()->user()->can('user_edit') || auth()->user()->can('user_delete')) {
            $columns[] = Column::make('action')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false);
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_'.date('YmdHis');
    }
}
