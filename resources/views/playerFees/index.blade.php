<x-layout>

    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <x-table-crud-card heading="Player Fees Management" subheading="Manage Player Fees" title="Record Player Fee"
                url="{{ route('player-fees.create') }}" id="addPlayerFeeBtn" :filters="[
                    [
                        'id' => 'playerFilter',
                        'placeholder' => 'All Players',
                        'class' => 'select2',
                        'options' => $players,
                    ],
                    [
                        'id' => 'batchFilter',
                        'placeholder' => 'All Batches',
                        'class' => 'select2',
                        'options' => $batches,
                    ],
                    [
                        'id' => 'monthFilter',
                        'placeholder' => 'All Months',
                        'class' => 'select2',
                        'default' => date('n'),
                        'options' => [
                            '1' => 'January',
                            '2' => 'February',
                            '3' => 'March',
                            '4' => 'April',
                            '5' => 'May',
                            '6' => 'June',
                            '7' => 'July',
                            '8' => 'August',
                            '9' => 'September',
                            '10' => 'October',
                            '11' => 'November',
                            '12' => 'December',
                        ],
                    ],
                
                    [
                        'id' => 'yearFilter',
                        'placeholder' => 'All Years',
                        'default' => date('Y'),
                        'options' => $years,
                    ],
                
                    [
                        'id' => 'statusFilter',
                        'placeholder' => 'All Statuses',
                        'options' => [
                            'paid' => 'Paid',
                            'pending' => 'Pending',
                        ],
                    ],
                
                    [
                        'id' => 'paymentTypeFilter',
                        'placeholder' => 'All Payments',
                        'options' => [
                            'upi' => 'UPI',
                            'cash' => 'Cash',
                            'card' => 'Card',
                        ],
                    ],
                ]">

                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0']) }}

            </x-table-crud-card>

        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
