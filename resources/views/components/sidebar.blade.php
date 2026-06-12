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

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                <i class="bi bi-grid nav-icon"></i>
                Dashboard
            </a>
        </li>

        <!-- User Management -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-people nav-icon"></i>
                User
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('players*') ? 'active' : '' }}" href="{{ route('players.index') }}">
                <i class="bi bi-person nav-icon"></i>
                Players
            </a>
        </li>

        <!-- Sports Management -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('sports*') ? 'active' : '' }}" href="{{ route('sports.index') }}">
                <i class="bi bi-trophy nav-icon"></i>
                Sports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('levels*') ? 'active' : '' }}" href="{{ route('levels.index') }}">
                <i class="bi bi-layers nav-icon"></i>
                Levels
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('sport-levels*') ? 'active' : '' }}"
                href="{{ route('sport-levels.index') }}">
                <i class="bi bi-grid-3x3-gap nav-icon"></i>
                Sports Levels
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('batches*') ? 'active' : '' }}" href="{{ route('batches.index') }}">
                <i class="bi bi-calendar-event nav-icon"></i>
                Batches
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('player-fees*') ? 'active' : '' }}"
                href="{{ route('player-fees.index') }}">
                <i class="bi bi-wallet2 nav-icon"></i>
                Fees
            </a>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                <i class="bi bi-gear nav-icon"></i>
                Settings
            </a>
        </li>
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>
