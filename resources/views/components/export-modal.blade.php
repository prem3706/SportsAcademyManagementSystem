@props([
    'id' => 'exportModal',
    'action' => '',
    'title' => 'Export Data',
    'fields' => [],
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form class="export-modal-form" action="{{ $action }}" method="POST">
                    @csrf

                    <!-- Select Fields Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <span class="small fw-bold text-secondary text-uppercase tracking-wider">Select
                                fields</span>
                            <div class="form-check mb-0">
                                <input class="form-check-input border-secondary select-all-fields" type="checkbox"
                                    id="{{ $id }}_selectAll" checked>
                                <label class="form-check-label small fw-bold text-secondary"
                                    for="{{ $id }}_selectAll">
                                    All Fields
                                </label>
                            </div>
                        </div>
                        <div class="row g-2 pt-1">
                            @foreach ($fields as $value => $label)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input field-checkbox" type="checkbox" name="columns[]"
                                            value="{{ $value }}"
                                            id="{{ $id }}_field_{{ $value }}" checked>
                                        <label class="form-check-label small text-dark fw-medium"
                                            for="{{ $id }}_field_{{ $value }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="btn btn-dark w-100 py-2 fw-semibold rounded-3 shadow-sm mt-2 submit-export-btn">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
