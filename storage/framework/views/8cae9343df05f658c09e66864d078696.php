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
    <div class="bg-body-tertiary vh-100 d-flex align-items-center overflow-hidden">

        <div class="container" style="max-width: 30rem;">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-4 p-md-4 bg-white rounded-4">

                    <!-- Heading -->
                    <div class="text-center mb-4">

                        <div class="bg-dark bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width:70px;height:70px;">

                            <i class="bi bi-person-fill fs-3"></i>

                        </div>

                        <h2 class="fw-bold mb-1 text-dark">
                            Login to your account
                        </h2>

                        <p class="text-secondary small mb-0">
                            Welcome back! Please sign in
                        </p>

                    </div>

                    <!-- Form -->
                    <form class="row g-3" action="<?php echo e(route('login')); ?>" method="POST" autocomplete="off">

                        <?php echo csrf_field(); ?>

                        <!-- Email -->
                        <div class="col-12">

                            <label class="form-label small fw-semibold text-dark">
                                Email Address
                            </label>

                            <div class="input-group shadow-sm rounded-3">

                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="bi bi-envelope text-secondary"></i>
                                </span>

                                <input class="form-control border-0 py-2 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="email" name="email" type="email" value="<?php echo e(old('email')); ?>"
                                    placeholder="your@email.com" autocomplete="off">

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

                        <!-- Password -->
                        <div class="col-12">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <label class="form-label small fw-semibold text-dark mb-0">
                                    Password
                                </label>

                                <a href="<?php echo e(route('forget.password.get')); ?>" class="small fw-semibold">

                                    Forgot Password?

                                </a>

                            </div>

                            <div class="input-group shadow-sm rounded-3">

                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="bi bi-lock text-secondary"></i>
                                </span>

                                <input class="form-control border-0 py-2 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="password" name="password" type="password" placeholder="Enter password"
                                    autocomplete="off">

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

                        <!-- Remember -->
                        <div class="col-12">

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" id="remember" name="remember">

                                <label class="form-check-label small text-secondary" for="remember">

                                    Remember me on this device

                                </label>

                            </div>

                        </div>

                        <!-- Button -->
                        <div class="col-12">

                            <button class="btn btn-dark w-100 py-2 fw-semibold rounded-3 shadow-sm" type="submit">

                                <i class="bi bi-box-arrow-in-right me-1"></i>

                                Sign In

                            </button>

                        </div>

                    </form>

                    <!-- Register -->
                    

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
<?php /**PATH C:\laragon\www\sams\resources\views/authentication/login.blade.php ENDPATH**/ ?>