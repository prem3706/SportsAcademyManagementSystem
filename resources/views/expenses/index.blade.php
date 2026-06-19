<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <x-table-crud-card heading="Expense Management" subheading="Manage all Expenses"
                title="Add Expense" :url="route('expenses.create')" id="addExpenseBtn" statusFilter="False"
                :bulkDeleteUrl="route('expenses.bulkDelete')"
                permission="expense">
                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
