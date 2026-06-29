<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-fluid px-4 py-3">

            <!-- Custom Card without table-responsive wrapper to avoid horizontal scrolling and hover clipping -->
            <div class="card border-0 shadow-sm rounded-4">
                <!-- Header -->
                <div class="card-header border-0 pt-4 pb-3 px-4 bg-white rounded-top-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                Import and Export Management
                            </h5>
                            <p class="text-secondary small mb-0">
                                Manage all Import and Export
                            </p>
                        </div>


                    </div>
                </div>

                <div class="card-body p-4 bg-white rounded-bottom-4">
                    <style>
                        @media (min-width: 768px) {
                            .import-section {
                                border-right: 1px solid #e2e8f0;
                            }
                        }

                        @media (max-width: 767.98px) {
                            .import-section {
                                border-bottom: 1px solid #e2e8f0;
                                padding-bottom: 1.5rem;
                                margin-bottom: 1.5rem;
                            }
                        }
                    </style>
                    <div class="row g-4">

                        <!-- Import Section -->
                        <div class="col-md-6 import-section pe-md-4">
                            <div class="p-2">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-4 p-3 me-3">
                                        <i class="bi bi-upload text-primary fs-2"></i>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                            Import Data</h5>
                                        <small class="text-secondary">
                                            Upload Excel or CSV files
                                        </small>
                                    </div>
                                </div>

                                <p class="text-secondary mb-3 small">
                                    Upload a single Excel file with data for all tables stacked vertically in a single
                                    sheet (e.g. <code>[Sports]</code>, <code>[Levels]</code>, <code>[Sport
                                        Levels]</code>, <code>[Expense Categories]</code>, <code>[Batches]</code>,
                                    <code>[Users]</code>, <code>[Expenses]</code>, <code>[Players]</code>).
                                </p>

                                <form id="verticalImportForm" method="POST"
                                    action="{{ route('import.export.preview') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="file" id="importFile" class="dropify"
                                            data-height="120" required data-allowed-file-extensions="xlsx xls csv" />
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary rounded-3 flex-grow-1"
                                            id="submitImportBtn"
                                            style="background-color: #4f46e5; border-color: #4f46e5;">
                                            <i class="bi bi-cloud-arrow-up me-2"></i>Preview Data
                                        </button>
                                        <a href="{{ route('import.export.download-sample') }}"
                                            class="btn btn-outline-primary rounded-3"
                                            style="color: #4f46e5; border-color: #4f46e5;">
                                            <i class="bi bi-download me-2"></i>Sample File
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Export Section -->
                        <div class="col-md-6 ps-md-4">
                            <div class="p-2">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 rounded-4 p-3 me-3">
                                        <i class="bi bi-download text-success fs-2"></i>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                            Export Data</h5>
                                        <small class="text-secondary">
                                            Download system reports
                                        </small>
                                    </div>
                                </div>

                                <p class="text-secondary mb-4">
                                    Export users, players, fees, and other records
                                    to Excel or CSV files.
                                </p>

                                <input type="hidden" id="exportCsrfToken" value="{{ csrf_token() }}">
                                <button type="button" class="btn btn-success rounded-3" id="showExportFieldsBtn">
                                    <i class="bi bi-download me-2"></i>
                                    Export Data
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Import & export Table Dynamic AJAX Container -->
            <div id="importExportContainer">
                <div class="card border-0 shadow-sm rounded-4 mt-4 animate__animated animate__fadeInUp">
                    <div class="card-header border-0 pt-4 pb-2 px-4 bg-white rounded-top-4">
                        <h6 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                            Expected File Structure (Vertical Stack)
                        </h6>
                        <p class="text-secondary small mb-0">
                            Your Excel file must contain all table data vertically stacked in the first sheet. Separate
                            tables using a blank row and label them with brackets as shown below:
                        </p>
                    </div>
                    <div class="card-body p-4 bg-white rounded-bottom-4">
                        <!-- Instruction Steps -->
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-2 small text-secondary">
                                <span class="badge bg-primary me-2">1</span>
                                <span>Each model section starts with a section header in brackets, e.g.,
                                    <code>[Sports]</code> in the first cell of a row.</span>
                            </div>
                            <div class="d-flex align-items-start mb-2 small text-secondary">
                                <span class="badge bg-primary me-2">2</span>
                                <span>The immediate next row contains the column field names (comma-separated).</span>
                            </div>
                            <div class="d-flex align-items-start mb-2 small text-secondary">
                                <span class="badge bg-primary me-2">3</span>
                                <span>Subsequent rows contain the actual records to import.</span>
                            </div>
                            <div class="d-flex align-items-start mb-2 small text-secondary">
                                <span class="badge bg-primary me-2">4</span>
                                <span>Leave a single empty/blank row before starting the next model section.</span>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 text-dark small"
                            style="letter-spacing: 0.5px; text-transform: uppercase;">Supported Models &amp; Fields</h6>

                        <div class="row g-3">
                            <!-- Sports -->
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-primary mb-1"><code>[Sports]</code></div>
                                    <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>name,
                                            description, status</code></div>
                                    <div class="small text-muted italic">E.g., Football, Football Academy, active</div>
                                </div>
                            </div>

                            <!-- Levels -->
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-success mb-1"><code>[Levels]</code></div>
                                    <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>name,
                                            status</code></div>
                                    <div class="small text-muted italic">E.g., Beginner, active</div>
                                </div>
                            </div>

                            <!-- Sport Levels -->
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 rounded-3 border bg-light h-100">
                                    <div class="fw-bold text-warning mb-1"><code>[Sport Levels]</code></div>
                                    <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>sport, level,
                                            fees</code></div>
                                    <div class="small text-muted italic">E.g., Football, Beginner, 500.00</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="small text-secondary">
                                <i class="bi bi-info-circle me-1 text-primary"></i>
                                You can download the template by clicking <strong><a
                                        href="{{ route('import.export.download-sample') }}">Sample File</a></strong> in
                                the Import card above to get a complete reference.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Import Results Modal -->
    <div class="modal fade" id="importResultsModal" tabindex="-1" aria-labelledby="importResultsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="importResultsModalLabel"
                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Settings Import Results
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

</x-layout>
