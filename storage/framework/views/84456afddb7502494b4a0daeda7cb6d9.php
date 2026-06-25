<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'importPlayersOffcanvas',
    'action' => '',
    'templateUrl' => '',
    'title' => 'Import Players'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => 'importPlayersOffcanvas',
    'action' => '',
    'templateUrl' => '',
    'title' => 'Import Players'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="<?php echo e($id); ?>" aria-labelledby="<?php echo e($id); ?>Label" style="width: 550px; max-width: 100%;">
    <!-- Header -->
    <div class="offcanvas-header border-bottom bg-white px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <!-- Left brand bar -->
            <div style="width: 4px; height: 18px; background-color: #4f46e5; border-radius: 2px;"></div>
            <h5 class="offcanvas-title fw-bold text-dark mb-0" id="<?php echo e($id); ?>Label" style="font-family: 'Plus Jakarta Sans', sans-serif;"><?php echo e($title); ?></h5>
        </div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Body -->
    <div class="offcanvas-body bg-light-subtle p-4 d-flex flex-column justify-content-between">
        <form id="importPlayersForm" action="<?php echo e($action); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <!-- Light blue instruction container exactly like screenshot -->
            <div class="p-3 mb-4 rounded-3 border-0" style="background-color: #e0f2fe; color: #0369a1; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem;">
                <div class="d-flex align-items-start gap-2 mb-3">
                    <i class="bi bi-info-circle-fill" style="font-size: 1.1rem; color: #0284c7; margin-top: 1px;"></i>
                    <div>
                        <span class="fw-bold" style="color: #0369a1;">Following fields are required and must be matched : <strong style="color: #0f172a;">First Name, Last Name, Phone, Joined At</strong></span>
                    </div>
                </div>

                <div class="mb-3">
                    <strong class="d-block mb-1" style="color: #0f172a; font-weight: 600;">Gender Format:</strong>
                    <span style="color: #334155; font-size: 0.825rem;">If the "gender" field is provided, it must be formatted as either "male", "female" or "other" (case-insensitive). This field is optional.</span>
                </div>

                <div class="mb-0">
                    <strong class="d-block mb-1" style="color: #0f172a; font-weight: 600;">Date of Birth Format:</strong>
                    <span style="color: #334155; font-size: 0.825rem;">If the "joined_at" (joined date) field is provided, it must be formatted as "YYYY-MM-DD" (e.g., "2026-06-23"). This field is required.</span>
                </div>
            </div>

            <!-- Download template -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($templateUrl): ?>
                <div class="mb-4">
                    <a href="<?php echo e($templateUrl); ?>" class="btn btn-outline-dark rounded-3 px-3 py-2 fw-semibold text-nowrap w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-file-earmark-arrow-down fs-5"></i>
                        Download Import Template
                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Drag & Drop upload container -->
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider">Choose File <span class="text-danger">*</span></label>
                <input type="file" name="file" id="importFile" class="dropify" data-height="160" required
                       data-allowed-file-extensions="xlsx xls csv" />
            </div>

            <!-- Action Button -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" id="submitImportBtn" style="background-color: #4f46e5; border-color: #4f46e5;">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <span>Import Players</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\sams\resources\views/components/import-offcanvas.blade.php ENDPATH**/ ?>