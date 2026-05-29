@props([
    'heading',
    'subheading',
    'id',
    'title',
    'url',
    'bulkDeleteUrl',
    'bulkUpdateUrl',
    'statusFilter' => 'False',
    'roleFilter' => 'False',
])

<div class="card border-0 shadow-sm rounded-4">

    <!-- Header -->
    <div class="card-header  border-0 pt-4 pb-3 px-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <!-- Title -->
            <div>
                <h5 class="fw-bold mb-1">
                    {{-- Users Management --}}
                    {{ $heading }}
                </h5>

                <p class="text-secondary small mb-0">
                    {{-- Manage all registered users --}}
                    {{ $subheading }}
                </p>
            </div>

            <!-- Actions -->
            <div class="d-flex align-items-center flex-wrap gap-2">

                <!-- Status Filter -->
                @if ($statusFilter === 'True')
                    <div style="min-width: 150px;">
                        <select id="statusFilter" class="form-select rounded-3 shadow-sm border-0">

                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>

                        </select>
                    </div>
                @endif

                @if ($roleFilter === 'True')
                    <!-- Role Filter -->
                    <div style="min-width: 150px;">
                        <select id="roleFilter" class="form-select rounded-3 shadow-sm border-0">

                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="player">Player</option>
                            <option value="coach">Coach</option>

                        </select>
                    </div>
                @endif

                <!-- Refresh -->
                <button type="button"
                    class="btn btn-light border shadow-sm rounded-3 px-3 d-flex align-items-center justify-content-center d-none"
                    id="refreshTableBtn">

                    <i class="bi bi-arrow-clockwise"></i>

                </button>

                <!-- Add User -->
                <button class="btn btn-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                    data-title="{{ $title }}" data-url="{{ $url }}" id="{{ $id }}"
                    type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">

                    <i class="bi bi-plus-circle me-1"></i>

                    {{ $title }}

                </button>

            </div>

        </div>

    </div>

    <!-- Table -->
    <div class="card-body  p-3">

        <form id="bulkDeleteForm">

            @csrf

            <div class="table-responsive">

                {{ $slot }}
            </div>

        </form>

    </div>

</div>

<!-- Bulk Action Bar -->
<div id="bulkActionBar"
    class="position-fixed bottom-0 start-50 translate-middle-x mb-4 bg-white border-0 shadow-lg rounded-4 px-4 py-3 d-none">

    <div class="d-flex align-items-center justify-content-between gap-4 flex-wrap">

        <!-- Left Side -->
        <div class="d-flex align-items-center gap-3 flex-wrap">

            <!-- Selected Count -->
            <div class="bg-dark text-white rounded-pill px-3 py-2 small fw-semibold d-flex align-items-center gap-2">

                <i class="bi bi-check2-square"></i>

                <span id="selectedCount">
                    0 Selected
                </span>

            </div>

            <!-- Status Dropdown -->
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

        </div>

        <!-- Right Side -->
        <div class="d-flex align-items-center gap-2">

            <!-- Update Button -->
            <button type="button" id="bulkUpdateBtn" data-url="{{ $bulkUpdateUrl }}"
                class="btn btn-success rounded-3 px-4 fw-semibold d-flex align-items-center gap-2">

                <i class="bi bi-check-circle"></i>

                Update

            </button>

            <!-- Delete Button -->
            <button type="button" id="bulkDeleteBtn" data-url="{{ $bulkDeleteUrl }}"
                class="btn btn-danger rounded-3 px-4 fw-semibold d-flex align-items-center gap-2">

                <i class="bi bi-trash"></i>

                Delete

            </button>

        </div>

    </div>

</div>
