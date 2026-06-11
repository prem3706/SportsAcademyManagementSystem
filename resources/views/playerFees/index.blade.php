<x-layout>

    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <x-table-crud-card heading="Player Fees Management" subheading="Manage Player Fees" title="Record Player Fee"
                url="{{ route('player-fees.create') }}"
                id="addPlayerFeeBtn"
                :filters="[
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
