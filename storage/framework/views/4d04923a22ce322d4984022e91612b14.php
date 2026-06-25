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

            <!-- Custom Card without table-responsive wrapper to avoid horizontal scrolling and hover clipping -->
            <div class="card border-0 shadow-sm rounded-4">
                <!-- Header -->
                <div class="card-header border-0 pt-4 pb-3 px-4 bg-white rounded-top-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                Role and Permission Management
                            </h5>
                            <p class="text-secondary small mb-0">
                                Manage all Roles and Permissions
                            </p>
                        </div>

                        <button class="btn btn-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                            data-title="Add Role" data-url="<?php echo e(route('roles.create')); ?>" id="addRoleBtn" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Role
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4 bg-white rounded-bottom-4">
                    <div class="row" id="rolesGrid">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 mb-3">
                                <div class="card role-card border-0 shadow-sm h-100" id="roleCard_<?php echo e($role->id); ?>">

                                    <div
                                        class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-1 px-3">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-bar"></div>

                                            <h6 class="mb-0 fw-bold text-dark"
                                                style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px;">
                                                <?php echo e($role->name); ?>

                                            </h6>
                                        </div>

                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary btn-sm rounded-circle p-0"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                style="width: 24px; height: 24px; line-height: 1;">
                                                ⋮
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                <li>
                                                    <a class="dropdown-item py-2" href="javascript:void(0)"
                                                        id="editRoleBtn" data-url="<?php echo e(route('roles.edit', $role->id)); ?>"
                                                        data-title="Edit Role" data-bs-toggle="offcanvas"
                                                        data-bs-target="#offcanvasScrolling">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Role
                                                    </a>
                                                </li>
                                                <?php
                                                    $defaultRoles = ['admin', 'manager', 'coach', 'player'];
                                                    $isDefault = in_array(strtolower($role->name), $defaultRoles);
                                                ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isDefault): ?>
                                                    <li>
                                                        <a class="dropdown-item py-2 text-danger"
                                                            href="javascript:void(0)" id="deleteRoleBtn"
                                                            data-url="<?php echo e(route('roles.destroy', $role->id)); ?>">
                                                            <i class="bi bi-trash me-2"></i> Delete Role
                                                        </a>
                                                    </li>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card-body px-3 pb-3 pt-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-secondary" style="font-size: 13px;">Permission</span>
                                            <a href="javascript:void(0)" class="theme-link view-permissions-btn"
                                                data-url="<?php echo e(route('roles.show', $role->id)); ?>"
                                                data-role-id="<?php echo e($role->id); ?>">
                                                View
                                            </a>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-secondary" style="font-size: 13px;">Member :</span>
                                            <strong class="text-dark"
                                                style="font-size: 13px;"><?php echo e($role->users_count); ?></strong>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Permissions Table Dynamic AJAX Container -->
            <div id="permissionsContainer"></div>

            <style>
                .custom-checkbox .form-check-input:checked {
                    background-color: #7c5cff !important;
                    border-color: #7c5cff !important;
                }

                .custom-checkbox .form-check-input {
                    border-color: #cbd5e1;
                    cursor: pointer;
                    width: 1.1em;
                    height: 1.1em;
                }

                .custom-checkbox .form-check-label {
                    cursor: pointer;
                    user-select: none;
                    padding-left: 0.15rem;
                }

                .table-hover tbody tr:hover {
                    background-color: #f8fafc;
                }
            </style>

        </div>

    </div>

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
<?php /**PATH C:\laragon\www\sams\resources\views/settings/roles-permissions.blade.php ENDPATH**/ ?>