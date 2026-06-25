<div class="d-flex justify-content-center">
    <form method="POST" id="editPlayerForm" style="max-width: 950px; width:100%;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <input type="hidden" name="url" id="url" value="<?php echo e(route('players.update', $player->id)); ?>">

        <div class="row g-3">
            <!-- First Name -->
            <div class="col-md-6">
                <label for="firstname" class="form-label fw-semibold small text-dark mb-2">First Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-person text-secondary"></i>
                    </span>
                    <input type="text" class="form-control py-2" id="firstname" name="firstname" value="<?php echo e($player->firstname); ?>" placeholder="Enter first name">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="firstnameError"></p>
                </div>
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
                <label for="lastname" class="form-label fw-semibold small text-dark mb-2">Last Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-person text-secondary"></i>
                    </span>
                    <input type="text" class="form-control py-2" id="lastname" name="lastname" value="<?php echo e($player->lastname); ?>" placeholder="Enter last name">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="lastnameError"></p>
                </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold small text-dark mb-2">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-envelope text-secondary"></i>
                    </span>
                    <input type="email" class="form-control py-2" id="email" name="email" value="<?php echo e($player->email); ?>" placeholder="Enter email address">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="emailError"></p>
                </div>
            </div>

            <!-- Phone -->
            <div class="col-md-6">
                <label for="phone" class="form-label fw-semibold small text-dark mb-2">Phone Number <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-telephone text-secondary"></i>
                    </span>
                    <input type="tel" class="form-control py-2" id="phone" name="phone" value="<?php echo e($player->phone); ?>" placeholder="Enter phone number">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="phoneError"></p>
                </div>
            </div>

            <!-- Gender -->
            <div class="col-md-6">
                <label for="gender" class="form-label fw-semibold small text-dark mb-2">Gender</label>
                <select class="form-select py-2" id="gender" name="gender">
                    <option value="" disabled <?php echo e(is_null($player->gender) ? 'selected' : ''); ?>>Select gender</option>
                    <option value="male" <?php echo e($player->gender == 'male' ? 'selected' : ''); ?>>Male</option>
                    <option value="female" <?php echo e($player->gender == 'female' ? 'selected' : ''); ?>>Female</option>
                    <option value="other" <?php echo e($player->gender == 'other' ? 'selected' : ''); ?>>Other</option>
                </select>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="genderError"></p>
                </div>
            </div>

            <!-- Joining Date -->
            <div class="col-md-6">
                <label for="joined_at" class="form-label fw-semibold small text-dark mb-2">Joining Date <span class="text-danger">*</span></label>
                <input type="text" class="form-control py-2 datepicker-input" id="joined_at" name="joined_at" value="<?php echo e($player->joined_at ? \Carbon\Carbon::parse($player->joined_at)->toDateString() : now()->toDateString()); ?>">
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="joined_atError"></p>
                </div>
            </div>

            <!-- Status selection -->
            <div class="col-12">
                <label for="status" class="form-label fw-semibold small text-dark mb-2">Status <span class="text-danger">*</span></label>
                <select class="form-select py-2" id="status" name="status">
                    <option value="active" <?php echo e($player->status == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e($player->status == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="statusError"></p>
                </div>
            </div>

            <!-- Sport & Batch Assignments Container -->
            <div class="col-12 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">Sport & Batch Assignments</h6>
                    <button type="button" class="btn btn-dark btn-sm rounded-3 fw-semibold" id="add_assignment_btn">
                        <i class="bi bi-plus-circle me-1"></i> Add Sport/Batch
                    </button>
                </div>

                <div id="assignments_container">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $playerBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $currentBatch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="card border border-secondary-subtle rounded-4 mb-3 p-3 assignment-row bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-secondary small">Assignment #<span class="row-num"><?php echo e($idx + 1); ?></span></span>
                                <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-assignment-btn <?php echo e(count($playerBatches) <= 1 ? 'd-none' : ''); ?>">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Sport <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm sport-select" name="assignments[<?php echo e($idx); ?>][sport_id]" required>
                                        <option value="" disabled>Select sport</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sport->id); ?>" <?php echo e($currentBatch->sport_id == $sport->id ? 'selected' : ''); ?>><?php echo e($sport->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Level <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm level-select" name="assignments[<?php echo e($idx); ?>][level_id]" required>
                                        <option value="" disabled>Select level</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $currentBatch->sport->levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $fees = $level->pivot ? $level->pivot->fees : 0;
                                            ?>
                                            <option value="<?php echo e($level->id); ?>" data-fees="<?php echo e($fees); ?>" <?php echo e($currentBatch->level_id == $level->id ? 'selected' : ''); ?>><?php echo e($level->name); ?> (Fees: ₹<?php echo e($fees); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Batch <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm batch-select" name="assignments[<?php echo e($idx); ?>][batch_id]" required>
                                        <option value="" disabled>Select batch</option>
                                        <?php
                                            $batches = \App\Models\Batch::where('sport_id', $currentBatch->sport_id)
                                                ->where('level_id', $currentBatch->level_id)
                                                ->where('status', 'active')
                                                ->get();
                                        ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($b->id); ?>" <?php echo e($currentBatch->id == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?> (<?php echo e($b->start_time); ?> - <?php echo e($b->end_time); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Joined Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm datepicker-input joined-date-input" name="assignments[<?php echo e($idx); ?>][joined_at]" value="<?php echo e($currentBatch->pivot->joined_at ? \Carbon\Carbon::parse($currentBatch->pivot->joined_at)->toDateString() : now()->toDateString()); ?>" required>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <!-- Default empty row if player has no batches yet -->
                        <div class="card border border-secondary-subtle rounded-4 mb-3 p-3 assignment-row bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-secondary small">Assignment #<span class="row-num">1</span></span>
                                <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-assignment-btn d-none">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Sport <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm sport-select" name="assignments[0][sport_id]" required>
                                        <option value="" disabled selected>Select sport</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sport->id); ?>"><?php echo e($sport->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Level <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm level-select" name="assignments[0][level_id]" required disabled>
                                        <option value="" disabled selected>Select sport first</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Batch <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm batch-select" name="assignments[0][batch_id]" required disabled>
                                        <option value="" disabled selected>Select level first</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-dark mb-1">Joined Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm datepicker-input joined-date-input" name="assignments[0][joined_at]" value="<?php echo e(now()->toDateString()); ?>" required>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="assignmentsError"></p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">
                    <i class="bi bi-check-circle me-1"></i> Update Player
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Helper container for copying sport options via JS -->
<div id="sport_options_helper" style="display:none;">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($sport->id); ?>"><?php echo e($sport->name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\sams\resources\views/players/editPlayerForm.blade.php ENDPATH**/ ?>