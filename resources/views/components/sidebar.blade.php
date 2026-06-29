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

        @can('dashboard_view')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                    <i class="bi bi-grid nav-icon"></i>
                    Dashboard
                </a>
            </li>
        @endcan

        @can('user_view')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people nav-icon"></i>
                    User
                </a>
            </li>
        @endcan

        @can('player_view')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('players*') ? 'active' : '' }}" href="{{ route('players.index') }}">
                    <i class="bi bi-person nav-icon"></i>
                    Players
                </a>
            </li>
        @endcan

        @if (auth()->user()->can('sport_view') || auth()->user()->can('level_view') || auth()->user()->can('sports_level_view'))
            <li
                class="nav-group {{ request()->is('sports*') || request()->is('levels*') || request()->is('sport-levels*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="bi bi-trophy nav-icon"></i>
                    Sports
                </a>

                <ul class="nav-group-items">

                    @can('sport_view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('sports*') ? 'active' : '' }}"
                                href="{{ route('sports.index') }}">
                                <span class="nav-icon">
                                    <i class="bi bi-trophy"></i>
                                </span>
                                Sports
                            </a>
                        </li>
                    @endcan

                    @can('level_view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('levels*') ? 'active' : '' }}"
                                href="{{ route('levels.index') }}">
                                <span class="nav-icon">
                                    <i class="bi bi-layers"></i>
                                </span>
                                Levels
                            </a>
                        </li>
                    @endcan

                    @can('sports_level_view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('sport-levels*') ? 'active' : '' }}"
                                href="{{ route('sport-levels.index') }}">
                                <span class="nav-icon">
                                    <i class="bi bi-grid-3x3-gap"></i>
                                </span>
                                Sports Levels
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endif

        @can('batch_view')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('batches*') ? 'active' : '' }}" href="{{ route('batches.index') }}">
                    <i class="bi bi-calendar-event nav-icon"></i>
                    Batches
                </a>
            </li>
        @endcan

        @can('fee_view')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('player-fees*') ? 'active' : '' }}"
                    href="{{ route('player-fees.index') }}">
                    <i class="bi bi-wallet2 nav-icon"></i>
                    Fees
                </a>
            </li>
        @endcan

        @canany(['expense_category_view', 'expense_view'])
            <li class="nav-group {{ request()->is('expense-category*') || request()->is('expenses*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="bi bi-cash-stack nav-icon"></i>
                    Finance
                </a>

                <ul class="nav-group-items">

                    @can('expense_category_view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('expense-category*') ? 'active' : '' }}"
                                href="{{ route('expense-category.index') }}">
                                <span class="nav-icon">
                                    <i class="bi bi-tags"></i>
                                </span>
                                Expense Categories
                            </a>
                        </li>
                    @endcan

                    @can('expense_view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}"
                                href="{{ route('expenses.index') }}">
                                <span class="nav-icon">
                                    <i class="bi bi-receipt"></i>
                                </span>
                                Expenses
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcanany

        @can('setting_view')
            <li
                class="nav-group {{ request()->is('settings*') || request()->is('role-permission*') || request()->is('import-export*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="bi bi-gear nav-icon"></i>
                    Settings
                </a>

                <ul class="nav-group-items">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('settings') ? 'active' : '' }}"
                            href="{{ route('settings.index') }}">
                            <span class="nav-icon">
                                <i class="bi bi-percent"></i>
                            </span>
                            Discount & Penalty
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('role-permission*') ? 'active' : '' }}"
                            href="{{ route('role.permission.index') }}">
                            <span class="nav-icon">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            Roles & Permissions
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('import-export*') ? 'active' : '' }}"
                            href="{{ route('import.export.index') }}">
                            <span class="nav-icon">
                                <i class="bi bi-upload"></i>
                            </span>
                            Import & Export
                        </a>
                    </li>

                </ul>
            </li>
        @endcan

    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>
