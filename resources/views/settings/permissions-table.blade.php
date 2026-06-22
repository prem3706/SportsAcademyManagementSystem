<!-- Permissions Table Card -->
<div class="card border-0 shadow-sm rounded-4 mt-4">
    <!-- Header -->
    <div class="card-header border-0 pt-4 pb-2 px-4 bg-white rounded-top-4">
        <div class="d-flex align-items-center">
            <div class="theme-bar" style="height: 22px; width: 4.5px;"></div>
            <h5 class="fw-bold mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                Permissions for "{{ ucfirst($selectedRole->name) }}"
            </h5>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4 bg-white rounded-bottom-4">
        <form id="savePermissionsForm" action="{{ route('roles.update', $selectedRole->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Hidden input to specify this is a permission update request -->
            <input type="hidden" name="permissions_update" value="1">

            <div class="table-responsive">
                <table class="table table-hover align-middle border-light-subtle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold px-4 py-3" style="width: 25%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; background-color: #f8fafc;">Module</th>
                            <th class="fw-bold px-4 py-3" style="width: 75%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; background-color: #f8fafc;">Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissionsByModule as $moduleName => $permissions)
                            <tr>
                                <td class="fw-semibold px-4 py-3 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px;">
                                    {{ $moduleName }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex flex-wrap gap-4">
                                        @foreach ($permissions as $permission)
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" type="checkbox"
                                                    id="permission_{{ $permission->id }}"
                                                    name="permissions[]"
                                                    value="{{ $permission->name }}"
                                                    {{ $selectedRole->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                    {{ $selectedRole->name === 'admin' ? 'disabled' : '' }}>
                                                <label class="form-check-label small text-dark fw-medium" for="permission_{{ $permission->id }}">
                                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Action Button -->
            @if ($selectedRole->name !== 'admin')
                <div class="d-flex justify-content-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 fw-semibold shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Save Changes
                    </button>
                </div>
            @else
                <div class="alert alert-info mt-3 small rounded-3 border-0">
                    <i class="bi bi-info-circle me-2"></i> The admin role permissions are protected and cannot be modified.
                </div>
            @endif
        </form>
    </div>
</div>
