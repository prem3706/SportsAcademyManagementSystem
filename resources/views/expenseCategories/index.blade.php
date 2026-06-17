<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <x-table-crud-card heading="Expense Category Management" subheading="Manage all Expense Categories"
                title="Add Expense Category" :url="route('expense-category.create')" id="addExpenseCategoryBtn" statusFilter="True"
                :bulkDeleteUrl="route('expense-category.bulkDelete')" :bulkUpdateUrl="route('expense-category.bulkUpdate')">
                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
