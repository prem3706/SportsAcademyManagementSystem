<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

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
                            data-title="Add Role" data-url="{{ route('roles.create') }}" id="addRoleBtn" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling">
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Role
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4 bg-white rounded-bottom-4">
                    <div class="row" id="rolesGrid">
                        @foreach ($roles as $role)
                            <div class="col-md-4 mb-3">
                                <div class="card role-card border-0 shadow-sm h-100" id="roleCard_{{ $role->id }}">

                                    <div
                                        class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-1 px-3">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-bar"></div>

                                            <h6 class="mb-0 fw-bold text-dark"
                                                style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px;">
                                                {{ $role->name }}
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
                                                        id="editRoleBtn" data-url="{{ route('roles.edit', $role->id) }}"
                                                        data-title="Edit Role" data-bs-toggle="offcanvas"
                                                        data-bs-target="#offcanvasScrolling">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Role
                                                    </a>
                                                </li>
                                                @php
                                                    $defaultRoles = ['admin', 'manager', 'coach', 'player'];
                                                    $isDefault = in_array(strtolower($role->name), $defaultRoles);
                                                @endphp
                                                @if (!$isDefault)
                                                    <li>
                                                        <a class="dropdown-item py-2 text-danger"
                                                            href="javascript:void(0)" id="deleteRoleBtn"
                                                            data-url="{{ route('roles.destroy', $role->id) }}">
                                                            <i class="bi bi-trash me-2"></i> Delete Role
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card-body px-3 pb-3 pt-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-secondary" style="font-size: 13px;">Permission</span>
                                            <a href="javascript:void(0)" class="theme-link view-permissions-btn"
                                                data-url="{{ route('roles.show', $role->id) }}"
                                                data-role-id="{{ $role->id }}">
                                                View
                                            </a>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-secondary" style="font-size: 13px;">Member :</span>
                                            <strong class="text-dark"
                                                style="font-size: 13px;">{{ $role->users_count }}</strong>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
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

</x-layout>
