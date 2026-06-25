<div class="d-flex justify-content-center">

    <form method="POST" id="addSportForm" data-width="medium" style="max-width: 950px; width:100%;">

        <?php echo csrf_field(); ?>

        <input type="hidden" name="url" id="url" value="<?php echo e(route('sports.store')); ?>">

        <div class="row g-3">

            <!-- Sport Name -->
            <div class="col-12">

                <label for="name" class="form-label fw-semibold small text-dark mb-2">
                    Sport Name <span class="text-danger">*</span>
                </label>

                <div class="input-group">

                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-trophy text-secondary"></i>
                    </span>

                    <input type="text"
                        class="form-control py-2"
                        id="name"
                        name="name"
                        placeholder="Enter sport name">

                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="nameError"></p>
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

            <!-- Description -->
            <div class="col-12">

                <label for="description" class="form-label fw-semibold small text-dark mb-2">
                    Description
                </label>

                <div class="input-group">

                    <span class="input-group-text bg-white px-3 align-items-start pt-3">
                        <i class="bi bi-card-text text-secondary"></i>
                    </span>

                    <textarea class="form-control py-2"
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Enter sport description"></textarea>

                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="descriptionError"></p>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-2">

                <button type="submit"
                    class="btn btn-dark w-100 py-2 fw-semibold rounded-3">

                    <i class="bi bi-check-circle me-1"></i>

                    Create Sport

                </button>

            </div>

        </div>

    </form>

</div>
<?php /**PATH C:\laragon\www\sams\resources\views/sports/addSportForm.blade.php ENDPATH**/ ?>