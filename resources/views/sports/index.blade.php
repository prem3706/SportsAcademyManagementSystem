<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <x-table-crud-card heading="Sports Management" subheading="Manage all Sports" title="Add Sports"
                :url="route('sports.create')" id="addSportBtn" statusFilter="True" :bulkDeleteUrl="route('sports.bulkDelete')" :bulkUpdateUrl="route('sports.bulkUpdate')"
                permission="sport">
                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
