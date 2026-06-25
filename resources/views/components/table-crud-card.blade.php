@props([
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
])

<div class="card border-0 shadow-sm rounded-4">

    <!-- Header -->
    <div class="card-header border-0 pt-4 pb-3 px-4">

        <!-- Row 1: Title and Add Button -->
        <div
            class="d-flex justify-content-between align-items-center flex-wrap gap-3 {{ $statusFilter === 'True' || $roleFilter === 'True' || count($filters) > 0 ? 'mb-3' : '' }}">

            <!-- Title -->
            <div>

                <h5 class="fw-bold mb-1">

                    {{ $heading }}

                </h5>

                <p class="text-secondary small mb-0">

                    {{ $subheading }}

                </p>

            </div>

            <!-- Add Button -->
            @php
                $canCreate = false;
            @endphp
            @if ($url)
                @if (!$permission)
                    @php $canCreate = true; @endphp
                @else
                    @can($permission . '_create')
                        @php $canCreate = true; @endphp
                    @endcan
                @endif
            @endif

            <div class="d-flex align-items-center gap-2">
                @if ($importUrl)
                    @if (!$permission || auth()->user()->can($permission . '_create'))
                        <button type="button" class="btn btn-outline-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                            id="importPlayerBtn" data-title="Import Players" data-url="{{ $importUrl }}"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">
                            <i class="bi bi-upload me-1"></i>
                            Import
                        </button>
                    @endif
                @endif

                @if ($exportUrl)
                    @if (!$permission || auth()->user()->can($permission . '_view'))
                        <button type="button" class="btn btn-outline-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                            data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="bi bi-download me-1"></i>
                            Export
                        </button>
                    @endif
                @endif

                @if ($canCreate)
                    <button class="btn btn-dark rounded-3 shadow-sm px-4 fw-semibold text-nowrap"
                        data-title="{{ $title }}" data-url="{{ $url }}" id="{{ $id }}" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">

                        <i class="bi bi-plus-circle me-1"></i>

                        {{ $title }}

                    </button>
                @endif
            </div>

        </div>

        <!-- Row 2: Filters and Refresh Button -->
        @if ($statusFilter === 'True' || $roleFilter === 'True' || count($filters) > 0)
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 border-top pt-3">

                <!-- Filters -->
                <div class="d-flex align-items-center flex-wrap gap-2">

                    <!-- Status Filter -->
                    @if ($statusFilter === 'True')
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
                    @endif

                    <!-- Role Filter -->
                    @if ($roleFilter === 'True')
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
                    @endif

                    <!-- Dynamic Filters -->
                    @foreach ($filters as $filter)
                        <div style="min-width: 150px;">

                            <select id="{{ $filter['id'] }}"
                                class="form-select select2 {{ $filter['id'] !== 'playerFilter' ? 'select2-no-search' : '' }} rounded-3 shadow-sm border-0 {{ $filter['class'] ?? '' }}">

                                <option value="">
                                    {{ $filter['placeholder'] }}
                                </option>

                                @foreach ($filter['options'] as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ ($filter['default'] ?? '') == $key ? 'selected' : '' }}>

                                        {{ $value }}

                                    </option>
                                @endforeach

                            </select>

                        </div>
                    @endforeach

                </div>
                <!-- Refresh -->
                <button type="button"
                    class="btn btn-light border shadow-sm rounded-3 px-3 d-flex align-items-center justify-content-center d-none"
                    id="refreshTableBtn">

                    <i class="bi bi-arrow-clockwise"></i>

                </button>

            </div>
        @endif

    </div>

    <!-- Table -->
    <div class="card-body p-3">

        <form id="bulkDeleteForm">

            @csrf

            <div class="table-responsive">

                {{ $slot }}

            </div>

        </form>

    </div>

</div>

<!-- Bulk Action Bar -->
@if (
    ($bulkDeleteUrl &&
        (!$permission ||
            auth()->user()->can($permission . '_delete'))) ||
        ($bulkUpdateUrl &&
            (!$permission ||
                auth()->user()->can($permission . '_edit'))))

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
                @if (
                    $bulkUpdateUrl &&
                        (!$permission ||
                            auth()->user()->can($permission . '_edit')))
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
                @endif

            </div>

            <!-- Right -->
            <div class="d-flex align-items-center gap-2">

                <!-- Update Button -->
                @if (
                    $bulkUpdateUrl &&
                        (!$permission ||
                            auth()->user()->can($permission . '_edit')))
                    <button type="button" id="bulkUpdateBtn" data-url="{{ $bulkUpdateUrl }}"
                        class="btn btn-success rounded-3 px-4 fw-semibold d-flex align-items-center gap-2">

                        <i class="bi bi-check-circle"></i>

                        Update

                    </button>
                @endif

                <!-- Delete Button -->
                @if (
                    $bulkDeleteUrl &&
                        (!$permission ||
                            auth()->user()->can($permission . '_delete')))
                    <button type="button" id="bulkDeleteBtn" data-url="{{ $bulkDeleteUrl }}"
                        class="btn btn-danger rounded-3 px-4 fw-semibold d-flex align-items-center gap-2">

                        <i class="bi bi-trash"></i>

                        Delete

                    </button>
                @endif

            </div>

        </div>

    </div>

@endif
