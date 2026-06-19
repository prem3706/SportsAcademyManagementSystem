<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            @php
                $playerFilters = [
                    [
                        'id' => 'sportFilter',
                        'placeholder' => 'All Sports',
                        'options' => $sports->pluck('name', 'id')->toArray(),
                    ],
                    [
                        'id' => 'levelFilter',
                        'placeholder' => 'All Levels',
                        'options' => $levels->pluck('name', 'id')->toArray(),
                    ],
                    [
                        'id' => 'batchFilter',
                        'placeholder' => 'All Batches',
                        'options' => $batches->pluck('name', 'id')->toArray(),
                    ],
                ];
            @endphp

            <x-table-crud-card heading="Players Management" subheading="Manage all Players" title="Add Player"
                :url="route('players.create')" id="addPlayerBtn" statusFilter="True"
                :filters="$playerFilters"
                :bulkDeleteUrl="route('players.bulkDelete')" :bulkUpdateUrl="route('players.bulkUpdate')"
                permission="player">
                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0']) }}

                
            </x-table-crud-card>


        </div>

    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
