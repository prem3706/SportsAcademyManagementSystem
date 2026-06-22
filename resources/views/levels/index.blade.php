<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <x-table-crud-card heading="Levels Management" subheading="Manage all Levels" title="Add Levels"
                :url="route('levels.create')" id="addLevelBtn" statusFilter="True" :bulkDeleteUrl="route('levels.bulkDelete')" :bulkUpdateUrl="route('levels.bulkUpdate')"
                permission="level">
                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
