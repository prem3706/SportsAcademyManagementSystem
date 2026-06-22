<div class="d-flex justify-content-center">

    <form method="POST" id="addRoleForm" data-width="medium" style="max-width: 950px; width:100%;">

        @csrf

        <input type="hidden" name="url" id="url" value="{{ route('roles.store') }}">

        <div class="row g-3">

            <!-- Role Name -->
            <div class="col-12">

                <label for="name" class="form-label fw-semibold small text-dark mb-2">
                    Role Name <span class="text-danger">*</span>
                </label>

                <div class="input-group">

                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-shield-check text-secondary"></i>
                    </span>

                    <input type="text"
                        class="form-control py-2"
                        id="name"
                        name="name"
                        placeholder="Enter role name (e.g. editor, assistant)">

                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="nameError"></p>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-2">

                <button type="submit"
                    class="btn btn-dark w-100 py-2 fw-semibold rounded-3">

                    <i class="bi bi-check-circle me-1"></i>

                    Create Role

                </button>

            </div>

        </div>

    </form>

</div>
