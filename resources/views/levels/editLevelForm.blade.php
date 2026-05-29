<div class="d-flex justify-content-center">

    <form method="POST" id="editLevelForm">

        @csrf
        @method('PUT')

        <input type="hidden" name="url" id="url" value="{{ route('levels.update', $level->id) }}">

        <div class="row g-3">

            <!-- Level Name -->
            <div class="col-12">

                <label class="form-label fw-semibold small text-dark mb-2">
                    Level Name
                </label>

                <div class="input-group">

                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-layers text-secondary"></i>
                    </span>

                    <input type="text" class="form-control py-2" name="name"
                        value="{{ old('name', $level->name) }}" placeholder="Enter level name">

                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="nameError"></p>
                </div>

            </div>

            <!-- Slug -->
            <div class="col-12">

                <label class="form-label fw-semibold small text-dark mb-2">
                    Slug
                </label>

                <div class="input-group">

                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-link-45deg text-secondary"></i>
                    </span>

                    <input type="text" class="form-control py-2" name="slug"
                        value="{{ old('slug', $level->slug) }}" placeholder="Enter slug">

                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="slugError"></p>
                </div>

            </div>

            <!-- Status -->
            <div class="col-md-12">

                <label for="status" class="form-label fw-semibold small text-dark mb-2">
                    Status
                </label>

                <select class="form-select py-2" id="status" name="status">

                    <option value="active" {{ old('status', $level->status) == 'active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="inactive" {{ old('status', $level->status) == 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="statusError"></p>
                </div>

            </div>

            <!-- Submit -->
            <div class="col-12 mt-3">

                <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">

                    <i class="bi bi-pencil-square me-1"></i>

                    Update Level

                </button>

            </div>

        </div>

    </form>

</div>
