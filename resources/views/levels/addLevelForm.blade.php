<div class="d-flex justify-content-center">

    <form method="POST" action="{{ route('levels.store') }}">

        @csrf




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

                    <input type="text" class="form-control py-2" name="name" placeholder="Enter level name">

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

                    <input type="text" class="form-control py-2" name="slug" placeholder="Enter slug">

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
