<style>
    .form-check-input:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
<div class="card border-0 shadow-sm rounded-4 mt-4 animate__animated animate__fadeInUp" id="exportSelectionCard">
    <form id="customExportFieldsForm" method="POST" action="{{ route('import.export.export') }}">
        @csrf
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0 rounded-top-4 flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.15rem;">
                    Export Data Selection
                </h5>
                <p class="text-secondary small mb-0 mt-1">Select the models and columns to export into a combined Excel spreadsheet.</p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <div class="form-check me-3 mb-0">
                    <input class="form-check-input" type="checkbox" id="globalSelectAllFields">
                    <label class="form-check-label fw-semibold text-secondary small cursor-pointer" for="globalSelectAllFields">
                        All Fields
                    </label>
                </div>
                <button type="button" class="btn btn-light px-3 py-1.5 fw-semibold border rounded-3 btn-sm" id="cancelExportSelectionBtn" style="font-size: 0.85rem;">
                    Cancel
                </button>
                <button type="submit" class="btn btn-success px-4 py-1.5 fw-semibold shadow-sm rounded-3 btn-sm" id="submitExportBtn" style="font-size: 0.85rem; background-color: #22c55e; border-color: #22c55e;">
                    <i class="bi bi-download me-1"></i> Export Excel
                </button>
            </div>
        </div>
        <div class="card-body p-4 bg-white rounded-bottom-4">
            <div class="row g-4">
                @foreach ($schema as $key => $model)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="mb-4">
                            <div class="pb-2 mb-2 border-bottom d-flex align-items-center justify-content-between">
                                <div class="form-check mb-0">
                                    <input class="form-check-input model-select-all" type="checkbox" id="model_all_{{ $key }}" data-model="{{ $key }}">
                                    <label class="form-check-label fw-bold text-dark small cursor-pointer" for="model_all_{{ $key }}" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        {{ $model['label'] }}
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                 @foreach ($model['fields'] as $colName => $colLabel)
                                    <div class="form-check py-1">
                                        <input class="form-check-input column-checkbox" type="checkbox" name="columns[{{ $key }}][]" value="{{ $colName }}" id="col_{{ $key }}_{{ $colName }}" data-model="{{ $key }}">
                                        <label class="form-check-label small text-secondary cursor-pointer text-nowrap" for="col_{{ $key }}_{{ $colName }}">
                                            {{ $colName }} <span class="text-muted">({{ $colLabel }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
</div>
