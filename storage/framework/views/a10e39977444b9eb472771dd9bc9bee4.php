<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'exportModal',
    'action' => '',
    'title' => 'Export Data',
    'fields' => [],
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
    'id' => 'exportModal',
    'action' => '',
    'title' => 'Export Data',
    'fields' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="modal fade" id="<?php echo e($id); ?>" tabindex="-1" aria-labelledby="<?php echo e($id); ?>Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="<?php echo e($id); ?>Label"><?php echo e($title); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form class="export-modal-form" action="<?php echo e($action); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- Select Fields Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <span class="small fw-bold text-secondary text-uppercase tracking-wider">Select
                                fields</span>
                            <div class="form-check mb-0">
                                <input class="form-check-input border-secondary select-all-fields" type="checkbox"
                                    id="<?php echo e($id); ?>_selectAll" checked>
                                <label class="form-check-label small fw-bold text-secondary"
                                    for="<?php echo e($id); ?>_selectAll">
                                    All Fields
                                </label>
                            </div>
                        </div>
                        <div class="row g-2 pt-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input field-checkbox" type="checkbox" name="columns[]"
                                            value="<?php echo e($value); ?>"
                                            id="<?php echo e($id); ?>_field_<?php echo e($value); ?>" checked>
                                        <label class="form-check-label small text-dark fw-medium"
                                            for="<?php echo e($id); ?>_field_<?php echo e($value); ?>">
                                            <?php echo e($label); ?>

                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH C:\laragon\www\sams\resources\views/components/export-modal.blade.php ENDPATH**/ ?>