<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand me-auto">
            <!-- Full Logo (Shown in normal state) -->
            <div class="sidebar-brand-full d-flex align-items-center gap-2 px-2">
                <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary text-white"
                    style="width: 32px; height: 32px; box-shadow: 0 4px 8px rgba(79, 70, 229, 0.25);">
                    <i class="bi bi-award-fill fs-5"></i>
                </div>
                <div class="text-start">
                    <span class="fw-bold text-white d-block"
                        style="font-size: 14px; letter-spacing: 0.5px; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.2;">SPORTS
                        ACADEMY</span>
                    <span
                        style="font-size: 9px; font-weight: 700; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; line-height: 1; display: block; margin-top: 2px;">Management</span>
                </div>
            </div>

            <!-- Narrow Logo (Shown in minimized/narrow state) -->
            <div class="sidebar-brand-narrow">
                <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary text-white"
                    style="width: 32px; height: 32px; box-shadow: 0 4px 8px rgba(79, 70, 229, 0.25);">
                    <i class="bi bi-award-fill fs-5"></i>
                </div>
            </div>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard_view')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('/') ? 'active' : ''); ?>" href="/">
                    <i class="bi bi-grid nav-icon"></i>
                    Dashboard
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_view')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>" href="<?php echo e(route('users.index')); ?>">
                    <i class="bi bi-people nav-icon"></i>
                    User
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('player_view')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('players*') ? 'active' : ''); ?>" href="<?php echo e(route('players.index')); ?>">
                    <i class="bi bi-person nav-icon"></i>
                    Players
                </a>
            </li>
        <?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->can('sport_view') || auth()->user()->can('level_view') || auth()->user()->can('sports_level_view')): ?>
            <li
                class="nav-group <?php echo e(request()->is('sports*') || request()->is('levels*') || request()->is('sport-levels*') ? 'show' : ''); ?>">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="bi bi-trophy nav-icon"></i>
                    Sports
                </a>

                <ul class="nav-group-items">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sport_view')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('sports*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('sports.index')); ?>">
                                <span class="nav-icon">
                                    <i class="bi bi-trophy"></i>
                                </span>
                                Sports
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('level_view')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('levels*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('levels.index')); ?>">
                                <span class="nav-icon">
                                    <i class="bi bi-layers"></i>
                                </span>
                                Levels
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sports_level_view')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('sport-levels*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('sport-levels.index')); ?>">
                                <span class="nav-icon">
                                    <i class="bi bi-grid-3x3-gap"></i>
                                </span>
                                Sports Levels
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('batch_view')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('batches*') ? 'active' : ''); ?>" href="<?php echo e(route('batches.index')); ?>">
                    <i class="bi bi-calendar-event nav-icon"></i>
                    Batches
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('fee_view')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('player-fees*') ? 'active' : ''); ?>"
                    href="<?php echo e(route('player-fees.index')); ?>">
                    <i class="bi bi-wallet2 nav-icon"></i>
                    Fees
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['expense_category_view', 'expense_view'])): ?>
            <li class="nav-group <?php echo e(request()->is('expense-category*') || request()->is('expenses*') ? 'show' : ''); ?>">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="bi bi-cash-stack nav-icon"></i>
                    Finance
                </a>

                <ul class="nav-group-items">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense_category_view')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('expense-category*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('expense-category.index')); ?>">
                                <span class="nav-icon">
                                    <i class="bi bi-tags"></i>
                                </span>
                                Expense Categories
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense_view')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->is('expenses*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('expenses.index')); ?>">
                                <span class="nav-icon">
                                    <i class="bi bi-receipt"></i>
                                </span>
                                Expenses
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting_view')): ?>
            <li class="nav-group <?php echo e(request()->is('settings*') ? 'show' : ''); ?>">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="bi bi-gear nav-icon"></i>
                    Settings
                </a>

                <ul class="nav-group-items">

                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->is('settings') ? 'active' : ''); ?>"
                            href="<?php echo e(route('settings.index')); ?>">
                            <span class="nav-icon">
                                <i class="bi bi-percent"></i>

                            </span>
                            Discount & Penalty

                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->is('role-permission') ? 'active' : ''); ?>"
                            href="<?php echo e(route('role.permission.index')); ?>">
                            <span class="nav-icon">
                                <i class="bi bi-shield-lock"></i>

                            </span>
                            Roles & Permissions

                        </a>
                    </li>

                </ul>
            </li>
        <?php endif; ?>

    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>
<?php /**PATH C:\laragon\www\sams\resources\views/components/sidebar.blade.php ENDPATH**/ ?>