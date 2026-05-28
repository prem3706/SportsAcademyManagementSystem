<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-lg py-3">

            <x-table-crud-card heading="Sports Management" subheading="Manage all Sports" title="Add Sports"
                :url="route('sports.create')" id="addSportBtn" statusFilter="True" :bulkDeleteUrl="route('sports.bulkDelete')" :bulkUpdateUrl="route('sports.bulkUpdate')">
                {{ $dataTable->table(['class' => 'table table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
