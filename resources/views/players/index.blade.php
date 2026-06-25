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
                :url="route('players.create')" :exportUrl="route('players.export')" :importUrl="route('players.importForm')" id="addPlayerBtn" statusFilter="True"
                :filters="$playerFilters" :bulkDeleteUrl="route('players.bulkDelete')" :bulkUpdateUrl="route('players.bulkUpdate')" permission="player">
                {{ $dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0']) }}


            </x-table-crud-card>


        </div>

    </div>

    <!-- Export Player Modal -->
    <x-export-modal id="exportModal" :action="route('players.export')" title="Export Player" :fields="[
        'firstname' => 'FirstName',
        'lastname' => 'LastName',
        'email' => 'Email',
        'phone' => 'Phone',
        'gender' => 'Gender',
        'status' => 'Status',
        'joined_at' => 'Joined_at',
        'sport' => 'Sport',
        'level' => 'Level',
        'batch' => 'Batch',
    ]" />

    <!-- Import Results Modal -->
    <div class="modal fade" id="importResultsModal" tabindex="-1" aria-labelledby="importResultsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="importResultsModalLabel"
                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Player Import Results
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success-subtle border-0 rounded-3 p-3 text-center">
                                <span class="d-block text-success fw-bold h4 mb-1" id="importSuccessCount">0</span>
                                <span class="text-secondary small fw-semibold">Successfully Imported</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger-subtle border-0 rounded-3 p-3 text-center">
                                <span class="d-block text-danger fw-bold h4 mb-1" id="importSkippedCount">0</span>
                                <span class="text-secondary small fw-semibold">Skipped / Errors</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-secondary-subtle border-0 rounded-3 p-3 text-center">
                                <span class="d-block text-dark fw-bold h4 mb-1" id="importTotalCount">0</span>
                                <span class="text-secondary small fw-semibold">Total Rows</span>
                            </div>
                        </div>
                    </div>

                    <div id="importErrorsContainer" class="d-none">
                        <h6 class="fw-bold text-danger mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill"></i> Import Errors & Skipped Rows
                        </h6>
                        <div class="border rounded-3 p-3 bg-light overflow-auto" style="max-height: 250px;">
                            <ul class="list-unstyled mb-0 small text-danger" id="importErrorsList"
                                style="line-height: 1.6;">
                                <!-- Errors will be appended here -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-semibold"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

</x-layout>
