<x-layout>

    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-lg py-3">

            <x-table-crud-card heading="Fees Generation Management" subheading="Generate Monthly Fees"
                title="Generate Fees" :url="route('fees-generates.create')" id="addFeesGenerateBtn">

                {{ $dataTable->table(['class' => 'table table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
