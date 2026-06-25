<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'heading',
    'subheading',

    // Add Button
    'id' => '',
    'title' => '',
    'url' => '',

    // Export Button
    'exportUrl' => '',

    // Import Button
    'importUrl' => '',

    // Bulk Actions
    'bulkDeleteUrl' => '',
    'bulkUpdateUrl' => '',

    // Filters
    'statusFilter' => 'False',
    'roleFilter' => 'False',

    'filters' => [],

    // Permission Prefix
    'permission' => null,
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
    'heading',
    'subheading',

    // Add Button
    'id' => '',
    'title' => '',
    'url' => '',

    // Export Button
    'exportUrl' => '',

    // Import Button
    'importUrl' => '',

    // Bulk Actions
    'bulkDeleteUrl' => '',
    'bulkUpdateUrl' => '',

    // Filters
    'statusFilter' => 'False',
    'roleFilter' => 'False',

    'filters' => [],

    // Permission Prefix
    'permission' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="card border-0 shadow-sm rounded-4">

    <!-- Header -->
    <div class="card-header border-0 pt-4 pb-3 px-4">

        <!-- Row 1: Title and Add Button -->
        <div
            class="d-flex justify-content-between align-items-center flex-wrap gap-3 <?php echo e($statusFilter === 'True' || $roleFilter === 'True' || count($filters) > 0 ? 'mb-3' : ''); ?>">

            <!-- Title -->
            <div>

                <h5 class="fw-bold mb-1">

                    <?php echo e($heading); ?>


                </h5>

                <p class="text-secondary small mb-0">

                    <?php echo e($subheading); ?>


                </p>

            </div>

            <!-- Add Button -->
            <?php
                $canCreate = false;
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($url): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$permission): ?>
                    <?php $canCreate = true; ?>
                <?php else: ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($permission . '_create')): ?>
                        <?php $canCreate = true; ?>
                    <?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="d-flex align-items-center gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($importUrl): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$permission || auth()->user()->can($permission . '_create')): ?>
                        <button type="button" class="btn btn-outline-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                            id="importPlayerBtn" data-title="Import Players" data-url="<?php echo e($importUrl); ?>"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">
                            <i class="bi bi-upload me-1"></i>
                            Import
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exportUrl): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$permission || auth()->user()->can($permission . '_view')): ?>
                        <button type="button" class="btn btn-outline-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                            data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="bi bi-download me-1"></i>
                            Export
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canCreate): ?>
                    <button class="btn btn-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                        data-title="<?php echo e($title); ?>" data-url="<?php echo e($url); ?>" id="<?php echo e($id); ?>" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">

                        <i class="bi bi-plus-circle me-1"></i>

                        <?php echo e($title); ?>


                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>

        <!-- Row 2: Filters and Refresh Button -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusFilter === 'True' || $roleFilter === 'True' || count($filters) > 0): ?>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 border-top pt-3">

                <!-- Filters -->
                <div class="d-flex align-items-center flex-wrap gap-2">

                    <!-- Status Filter -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusFilter === 'True'): ?>
                        <div style="min-width: 150px;">

                            <select id="statusFilter"
                                class="form-select select2 select2-no-search rounded-3 shadow-sm border-0">

                                <option value="">
                                    All Status
                                </option>

                                <option value="active">
                                    Active
                                </option>

                                <option value="inactive">
                                    Inactive
                                </option>

                            </select>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Role Filter -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($roleFilter === 'True'): ?>
                        <div style="min-width: 150px;">

                            <select id="roleFilter"
                                class="form-select select2 select2-no-search rounded-3 shadow-sm border-0">

                                <option value="">
                                    All Roles
                                </option>

                                <option value="admin">
                                    Admin
                                </option>

                                <option value="player">
                                    Player
                                </option>

                                <option value="coach">
                                    Coach
                                </option>

                                <option value="manager">
                                    Manager
                                </option>

                            </select>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Dynamic Filters -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="min-width: 150px;">

                            <select id="<?php echo e($filter['id']); ?>"
                                class="form-select select2 <?php echo e($filter['id'] !== 'playerFilter' ? 'select2-no-search' : ''); ?> rounded-3 shadow-sm border-0 <?php echo e($filter['class'] ?? ''); ?>">

                                <option value="">
                                    <?php echo e($filter['placeholder']); ?>

                                </option>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filter['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e(($filter['default'] ?? '') == $key ? 'selected' : ''); ?>>

                                        <?php echo e($value); ?>


                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </select>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>
                <!-- Refresh -->
                <button type="button"
                    class="btn btn-light border shadow-sm rounded-3 px-3 d-flex align-items-center justify-content-center d-none"
                    id="refreshTableBtn">

                    <i class="bi bi-arrow-clockwise"></i>

                </button>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    <!-- Table -->
    <div class="card-body p-3">

        <form id="bulkDeleteForm">

            <?php echo csrf_field(); ?>

            <div class="table-responsive">

                <?php echo e($slot); ?>


            </div>

        </form>

    </div>

</div>

<!-- Bulk Action Bar -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
    ($bulkDeleteUrl &&
        (!$permission ||
            auth()->user()->can($permission . '_delete'))) ||
        ($bulkUpdateUrl &&
            (!$permission ||
                auth()->user()->can($permission . '_edit')))): ?>

    <div id="bulkActionBar"
        class="position-fixed bottom-0 start-50 translate-middle-x mb-4 bg-white border-0 shadow-lg rounded-4 px-4 py-3 d-none">

        <div class="d-flex align-items-center justify-content-between gap-4 flex-wrap">

            <!-- Left -->
            <div class="d-flex align-items-center gap-3 flex-wrap">

                <!-- Selected Count -->
                <div
                    class="bg-dark text-white rounded-pill px-3 py-2 small fw-semibold d-flex align-items-center gap-2">

                    <i class="bi bi-check2-square"></i>

                    <span id="selectedCount">

                        0 Selected

                    </span>

                </div>

                <!-- Status Update -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                    $bulkUpdateUrl &&
                        (!$permission ||
                            auth()->user()->can($permission . '_edit'))): ?>
                    <div style="min-width: 180px;">

                        <select id="statusUpdate" class="form-select rounded-3 border-0 shadow-sm">

                            <option value="">
                                Update Status
                            </option>

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>

            <!-- Right -->
            <div class="d-flex align-items-center gap-2">

                <!-- Update Button -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                    $bulkUpdateUrl &&
                        (!$permission ||
                            auth()->user()->can($permission . '_edit'))): ?>
                    <button type="button" id="bulkUpdateBtn" data-url="<?php echo e($bulkUpdateUrl); ?>"
                        class="btn btn-success rounded-3 px-4 fw-semibold d-flex align-items-center gap-2">

                        <i class="bi bi-check-circle"></i>

                        Update

                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Delete Button -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                    $bulkDeleteUrl &&
                        (!$permission ||
                            auth()->user()->can($permission . '_delete'))): ?>
                    <button type="button" id="bulkDeleteBtn" data-url="<?php echo e($bulkDeleteUrl); ?>"
                        class="btn btn-danger rounded-3 px-4 fw-semibold d-flex align-items-center gap-2">

                        <i class="bi bi-trash"></i>

                        Delete

                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>

        </div>

    </div>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\sams\resources\views/components/table-crud-card.blade.php ENDPATH**/ ?>