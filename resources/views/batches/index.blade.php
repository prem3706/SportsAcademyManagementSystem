<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-lg py-3">

            <x-table-crud-card heading="Batches Management" subheading="Manage all Batches" title="Add Batches"
                :url="route('batches.create')" id="addBatchBtn" :bulkDeleteUrl="route('sports.bulkDelete')" :bulkUpdateUrl="route('sports.bulkUpdate')">
                {{-- {{ $dataTable->table(['class' => 'table table-hover align-middle mb-0']) }} --}}

            </x-table-crud-card>

        </div>

    </div>

    {{-- @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush --}}

</x-layout>
