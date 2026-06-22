<div class="d-flex justify-content-center">

    <form method="POST" id="addLevelForm" data-width="medium">

        @csrf
        <input type="hidden" name="url" id="url" value="{{ route('levels.store') }}">


        <div class="row g-3">

            <!-- Level Name -->
            <div class="col-12">

                <label class="form-label fw-semibold small text-dark mb-2">
                    Level Name <span class="text-danger">*</span>
                </label>

                <div class="input-group">

                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-layers text-secondary"></i>
                    </span>

                    <input type="text" class="form-control py-2" name="name" placeholder="Enter level name">

                </div>

            </div>


            <!-- Status -->
            <div class="col-md-12">

                <label for="status" class="form-label fw-semibold small text-dark mb-2">
                    Status <span class="text-danger">*</span>
                </label>

                <select class="form-select py-2" id="status" name="status">

                    <option value="active" selected>
                        Active
                    </option>

                    <option value="inactive">
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

                    <i class="bi bi-check-circle me-1"></i>

                    Create Level

                </button>

            </div>

        </div>



    </form>

</div>


