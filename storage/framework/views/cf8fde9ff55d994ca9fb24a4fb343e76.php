<?php if (isset($component)) { $__componentOriginal1f9e5f64f242295036c059d9dc1c375c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c = $attributes; } ?>
<?php $component = App\View\Components\Layout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginald31f0a1d6e85408eecaaa9471b609820 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald31f0a1d6e85408eecaaa9471b609820 = $attributes; } ?>
<?php $component = App\View\Components\Sidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Sidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $attributes = $__attributesOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__attributesOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald31f0a1d6e85408eecaaa9471b609820)): ?>
<?php $component = $__componentOriginald31f0a1d6e85408eecaaa9471b609820; ?>
<?php unset($__componentOriginald31f0a1d6e85408eecaaa9471b609820); ?>
<?php endif; ?>

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <?php if (isset($component)) { $__componentOriginalb9eddf53444261b5c229e9d8b9f1298e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb9eddf53444261b5c229e9d8b9f1298e = $attributes; } ?>
<?php $component = App\View\Components\Navbar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Navbar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb9eddf53444261b5c229e9d8b9f1298e)): ?>
<?php $attributes = $__attributesOriginalb9eddf53444261b5c229e9d8b9f1298e; ?>
<?php unset($__attributesOriginalb9eddf53444261b5c229e9d8b9f1298e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb9eddf53444261b5c229e9d8b9f1298e)): ?>
<?php $component = $__componentOriginalb9eddf53444261b5c229e9d8b9f1298e; ?>
<?php unset($__componentOriginalb9eddf53444261b5c229e9d8b9f1298e); ?>
<?php endif; ?>

        <div class="container-fluid px-4 py-3">

            <?php
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
            ?>

            <?php if (isset($component)) { $__componentOriginalcb78580463d04cbe300f9d24807bdea6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb78580463d04cbe300f9d24807bdea6 = $attributes; } ?>
<?php $component = App\View\Components\TableCrudCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-crud-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\TableCrudCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Players Management','subheading' => 'Manage all Players','title' => 'Add Player','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('players.create')),'exportUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('players.export')),'importUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('players.importForm')),'id' => 'addPlayerBtn','statusFilter' => 'True','filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($playerFilters),'bulkDeleteUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('players.bulkDelete')),'bulkUpdateUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('players.bulkUpdate')),'permission' => 'player']); ?>
                <?php echo e($dataTable->table(['class' => 'table table-sm table-hover align-middle mb-0'])); ?>



             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb78580463d04cbe300f9d24807bdea6)): ?>
<?php $attributes = $__attributesOriginalcb78580463d04cbe300f9d24807bdea6; ?>
<?php unset($__attributesOriginalcb78580463d04cbe300f9d24807bdea6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb78580463d04cbe300f9d24807bdea6)): ?>
<?php $component = $__componentOriginalcb78580463d04cbe300f9d24807bdea6; ?>
<?php unset($__componentOriginalcb78580463d04cbe300f9d24807bdea6); ?>
<?php endif; ?>


        </div>

    </div>

    <!-- Export Player Modal -->
    <?php if (isset($component)) { $__componentOriginal298e4bd81774a252458e6ac6784dd2ba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal298e4bd81774a252458e6ac6784dd2ba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.export-modal','data' => ['id' => 'exportModal','action' => route('players.export'),'title' => 'Export Player','fields' => [
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
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('export-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'exportModal','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('players.export')),'title' => 'Export Player','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
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
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal298e4bd81774a252458e6ac6784dd2ba)): ?>
<?php $attributes = $__attributesOriginal298e4bd81774a252458e6ac6784dd2ba; ?>
<?php unset($__attributesOriginal298e4bd81774a252458e6ac6784dd2ba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal298e4bd81774a252458e6ac6784dd2ba)): ?>
<?php $component = $__componentOriginal298e4bd81774a252458e6ac6784dd2ba; ?>
<?php unset($__componentOriginal298e4bd81774a252458e6ac6784dd2ba); ?>
<?php endif; ?>

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

    <?php $__env->startPush('scripts'); ?>
        <?php echo e($dataTable->scripts()); ?>

    <?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $attributes = $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $component = $__componentOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sams\resources\views/players/index.blade.php ENDPATH**/ ?>