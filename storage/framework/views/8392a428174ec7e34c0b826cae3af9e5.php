<style>
    th.column-skipped {
        background-color: #e2e8f0 !important;
        transition: all 0.2s ease;
    }

    th.column-skipped .mapping-select {
        opacity: 0.6;
        background-color: #f1f5f9;
    }

    td.column-skipped {
        opacity: 0.45 !important;
        background-color: #f8fafc !important;
        text-decoration: line-through;
        color: #94a3b8 !important;
        transition: all 0.2s ease;
    }

    .mapping-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    .skip-column-btn {
        transition: all 0.15s ease-in-out;
    }

    .skip-column-btn:hover {
        transform: scale(1.05);
    }

    #excelPreviewTable thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8fafc !important;
        box-shadow: inset 0 -2px 0 #e2e8f0;
    }

    .btn-sample-download {
        color: #0369a1;
        border-color: rgba(3, 105, 161, 0.3);
        background-color: transparent;
        transition: all 0.2s ease;
    }
    .btn-sample-download:hover {
        color: #ffffff !important;
        background-color: #0284c7;
        border-color: #0284c7;
    }
</style>

<form method="POST" id="readImportPlayersForm" action="<?php echo e(route('players.readExcel')); ?>" enctype="multipart/form-data"
    class="w-100">
    <?php echo csrf_field(); ?>

    <input type="hidden" name="url" id="url" value="<?php echo e(route('players.readExcel')); ?>">
    <input type="hidden" name="import_url" id="importUrl" value="<?php echo e(route('players.import')); ?>">
    <input type="hidden" name="import_token" id="importToken" value="">

    <!-- Light blue instruction container exactly like screenshot -->
    <div id="instructionsContainer" class="p-3 mb-4 rounded-3 border-0"
        style="background-color: #e0f2fe; color: #0369a1; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem;">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
            <div class="d-flex gap-2">
                <i class="bi bi-info-circle-fill" style="font-size: 1.1rem; color: #0284c7; margin-top: 1px;"></i>
                <div>
                    <span class="fw-bold" style="color: #0369a1;">Following fields are required and must be matched :
                        <strong style="color: #0f172a;">First Name, Last Name, Phone, Joined At</strong></span>
                </div>
            </div>
            <a href="<?php echo e(asset('storage/exports/players_import_sample.xlsx')); ?>" download="players_import_sample.xlsx" class="btn btn-sample-download btn-sm rounded-3 fw-semibold text-nowrap d-flex align-items-center gap-1" style="font-size: 0.775rem;">
                <i class="bi bi-download"></i> Download Sample Excel
            </a>

        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <strong class="d-block mb-1" style="color: #0f172a; font-weight: 600;">Gender Format:</strong>
                <span style="color: #334155; font-size: 0.8rem;">If the "gender" field is provided, it must be formatted
                    as either "male", "female" or "other" (case-insensitive). This field is optional.</span>
            </div>
            <div class="col-md-6">
                <strong class="d-block mb-1" style="color: #0f172a; font-weight: 600;">Date Format:</strong>
                <span style="color: #334155; font-size: 0.8rem;">If the "joined_at" (joined date) field is provided, it
                    must be formatted as "YYYY-MM-DD" (e.g., "2026-06-23"). This field is required.</span>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <strong class="d-block mb-1" style="color: #0f172a; font-weight: 600;">Sport, Level, Batch:</strong>
                <span style="color: #334155; font-size: 0.8rem;">Must match active records in the system
                    (case-insensitive). Must map all three together to assign player to a batch.</span>
            </div>
            <div class="col-md-6">
                <strong class="d-block mb-1" style="color: #0f172a; font-weight: 600;">Status Format:</strong>
                <span style="color: #334155; font-size: 0.8rem;">Either "active" or "inactive". Defaults to "active" if
                    empty or not mapped.</span>
            </div>
        </div>

        <div class="mt-3 pt-3 border-top" style="border-top-color: rgba(3, 105, 161, 0.15) !important;">
            <strong class="d-block mb-2" style="color: #0f172a; font-weight: 600; font-size: 0.825rem;">Expected Excel /
                CSV Structure:</strong>
            <div class="table-responsive rounded-2 border" style="border-color: rgba(3, 105, 161, 0.2) !important;">
                <table class="table table-sm table-bordered text-center mb-0"
                    style="font-size: 0.725rem; border-color: rgba(3, 105, 161, 0.15);">
                    <thead style="background-color: #bae6fd; color: #0369a1; border-color: rgba(3, 105, 161, 0.2);">
                        <tr>
                            <th class="fw-semibold px-2 py-1">First Name</th>
                            <th class="fw-semibold px-2 py-1">Last Name</th>
                            <th class="fw-semibold px-2 py-1">Email</th>
                            <th class="fw-semibold px-2 py-1">Phone</th>
                            <th class="fw-semibold px-2 py-1">Gender</th>
                            <th class="fw-semibold px-2 py-1">Joined At</th>
                            <th class="fw-semibold px-2 py-1">Sport</th>
                            <th class="fw-semibold px-2 py-1">Level</th>
                            <th class="fw-semibold px-2 py-1">Batch</th>
                            <th class="fw-semibold px-2 py-1">Status</th>
                        </tr>
                    </thead>
                    <tbody style="color: #334155; background-color: #f8fafc;">
                        <tr>
                            <td class="px-2 py-1">John</td>
                            <td class="px-2 py-1">Doe</td>
                            <td class="px-2 py-1 text-muted">john@example.com</td>
                            <td class="px-2 py-1">1234567890</td>
                            <td class="px-2 py-1">male</td>
                            <td class="px-2 py-1">2026-06-23</td>
                            <td class="px-2 py-1">Football</td>
                            <td class="px-2 py-1">Beginner</td>
                            <td class="px-2 py-1">Football Junior morning</td>
                            <td class="px-2 py-1">active</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Drag & Drop upload container -->
    <div id="uploadContainer" class="mb-4">
        <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider">Choose File <span
                class="text-danger">*</span></label>
        <input type="file" name="file" id="importFile" class="dropify" data-height="160" required
            data-allowed-file-extensions="xlsx xls csv" />
        <div style="height:10px;">
            <p class="text-danger small mb-0" id="fileError"></p>
        </div>
    </div>

    <!-- Action Button -->
    <div id="readExcelActionContainer" class="mt-4">
        <button type="submit"
            class="btn btn-primary w-100 py-2.5 fw-semibold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"
            id="submitImportBtn" style="background-color: #4f46e5; border-color: #4f46e5;">
            <i class="bi bi-cloud-arrow-up"></i>
            <span>Import Players</span>
        </button>
    </div>

    <!-- Excel Mapping Preview Section (Initially Hidden) -->
    <div id="excelMappingContainer" class="d-none ">
        <div class="card border border-light-subtle rounded-3 shadow-sm my-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                <h5 class="fw-bold mb-0 text-dark"
                    style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.15rem;">
                    Excel Data
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light px-3 py-1.5 fw-semibold border rounded-3"
                        id="clearPreviewBtn" style="font-size: 0.875rem;">
                        Clear
                    </button>
                    <button type="button" class="btn btn-primary px-4 py-1.5 fw-semibold shadow-sm rounded-3"
                        id="saveImportBtn"
                        style="background-color: #4f46e5; border-color: #4f46e5; font-size: 0.875rem;">
                        Save
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow: auto;">
                    <table class="table table-hover align-middle mb-0 text-nowrap" id="excelPreviewTable">
                        <thead>
                            <tr id="mappingHeaderRow">
                                <!-- Dynamic column mapping selectors will be injected here -->
                            </tr>
                        </thead>
                        <tbody id="previewTableBody">
                            <!-- Dynamic preview rows will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<?php /**PATH C:\laragon\www\sams\resources\views/players/importForm.blade.php ENDPATH**/ ?>