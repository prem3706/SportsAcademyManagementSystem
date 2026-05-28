<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-lg py-3">

            <x-table-crud-card heading="Users Management" subheading="Manage all Users" title="Add User" :url="route('users.create')"
                id="addUserBtn" statusFilter="True" roleFilter="True" :bulkDeleteUrl="route('users.bulkDelete')" :bulkUpdateUrl="route('users.bulkUpdate')">
                {{ $dataTable->table(['class' => 'table table-hover align-middle mb-0']) }}

            </x-table-crud-card>


        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
