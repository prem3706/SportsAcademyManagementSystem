<x-layout>

    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-lg py-3">

            <x-table-crud-card heading="Player Fees Management" subheading="Manage Player Fees" title="Player Fees"
                :filters="[
                    [
                        'id' => 'sportFilter',
                        'placeholder' => 'All Sports',
                        'options' => $sports,
                    ],
                
                    [
                        'id' => 'monthFilter',
                        'placeholder' => 'All Months',
                        'options' => [
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December',
                        ],
                    ],
                
                    [
                        'id' => 'yearFilter',
                        'placeholder' => 'All Years',
                        'options' => [
                            2025 => '2025',
                            2026 => '2026',
                            2027 => '2027',
                        ],
                    ],
                ]">

                {{ $dataTable->table(['class' => 'table table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
