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
    <div class="bg-body-tertiary min-vh-100 d-flex align-items-center py-3">

        <div class="container-fluid px-3">

            <div class="row justify-content-center">

                <!-- Width Increased -->
                <div class="col-12 col-xl-9 col-xxl-8">

                    <div class="card border-0 shadow-lg rounded-4">

                        <div class="card-body p-4 p-md-4 bg-white rounded-4">

                            <!-- Heading -->
                            <div class="text-center mb-4">

                                <h2 class="fw-bold mb-1 fs-3 text-dark">
                                    Create New Account
                                </h2>

                                <p class="text-secondary small mb-0">
                                    Fill in your details to continue
                                </p>

                            </div>

                            <!-- Form -->
                            <form class="row g-3" action="<?php echo e(route('register')); ?>" method="POST"
                                enctype="multipart/form-data">

                                <?php echo csrf_field(); ?>

                                <!-- First Name -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        First Name
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-person text-secondary"></i>
                                        </span>

                                        <input type="text" name="firstname" value="<?php echo e(old('firstname')); ?>"
                                            placeholder="First name"
                                            class="form-control border-0 py-2 <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Last Name
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-person text-secondary"></i>
                                        </span>

                                        <input type="text" name="lastname" value="<?php echo e(old('lastname')); ?>"
                                            placeholder="Last name"
                                            class="form-control border-0 py-2 <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <!-- Email -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Email Address
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-envelope text-secondary"></i>
                                        </span>

                                        <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                                            placeholder="your@email.com"
                                            class="form-control border-0 py-2 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Phone Number
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-telephone text-secondary"></i>
                                        </span>

                                        <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>"
                                            placeholder="Phone number"
                                            class="form-control border-0 py-2 <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Gender
                                    </label>

                                    <div class="shadow-sm rounded-3">

                                        <select name="gender"
                                            class="form-select border-0 py-2 <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                            <option value="">
                                                Select gender
                                            </option>

                                            <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>
                                                Male
                                            </option>

                                            <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>
                                                Female
                                            </option>

                                            <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>
                                                Other
                                            </option>

                                        </select>

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <!-- Profile Picture -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Profile Picture
                                    </label>

                                    <div class="shadow-sm rounded-3">

                                        <input type="file" name="profile_picture"
                                            class="form-control border-0 py-2 <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <!-- Password -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Password
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-lock text-secondary"></i>
                                        </span>

                                        <input id="password" type="password" name="password"
                                            placeholder="Enter password"
                                            class="form-control border-0 py-2 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                                        <span class="input-group-text bg-white border-0" id="togglePassword"
                                            style="cursor:pointer;">

                                            <i class="bi bi-eye-slash text-secondary" id="toggleIcon"></i>

                                        </span>

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Confirm Password
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-shield-lock text-secondary"></i>
                                        </span>

                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                            placeholder="Confirm password" class="form-control border-0 py-2">

                                    </div>

                                </div>

                                <!-- Terms -->
                                <div class="col-12">

                                    <div class="form-check">

                                        <input type="checkbox" name="terms" class="form-check-input">

                                        <label class="form-check-label small text-secondary">

                                            I accept the

                                            <a href="#" class="text-decoration-none fw-semibold text-dark">

                                                terms & conditions

                                            </a>

                                        </label>

                                    </div>

                                </div>

                                <!-- Submit -->
                                <div class="col-12">

                                    <button type="submit"
                                        class="btn btn-dark w-100 py-2 rounded-3 fw-semibold shadow-sm">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Create Account

                                    </button>

                                </div>

                            </form>

                            <!-- Login -->
                            <div class="text-center mt-4 small text-secondary">

                                Already have an account?

                                <a href="<?php echo e(route('login')); ?>" class="text-decoration-none fw-semibold text-dark">

                                    Sign In

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

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
<?php /**PATH C:\laragon\www\sams\resources\views/authentication/register.blade.php ENDPATH**/ ?>