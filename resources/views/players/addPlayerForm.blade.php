<div class="d-flex justify-content-center">
    <form method="POST" id="addPlayerForm" style="max-width: 950px; width:100%;">
        @csrf

        <input type="hidden" name="url" id="url" value="{{ route('players.store') }}">

        <div class="row g-3">
            <!-- First Name -->
            <div class="col-md-6">
                <label for="firstname" class="form-label fw-semibold small text-dark mb-2">First Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-person text-secondary"></i>
                    </span>
                    <input type="text" class="form-control py-2" id="firstname" name="firstname" placeholder="Enter first name">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="firstnameError"></p>
                </div>
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
                <label for="lastname" class="form-label fw-semibold small text-dark mb-2">Last Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-person text-secondary"></i>
                    </span>
                    <input type="text" class="form-control py-2" id="lastname" name="lastname" placeholder="Enter last name">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="lastnameError"></p>
                </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold small text-dark mb-2">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-envelope text-secondary"></i>
                    </span>
                    <input type="email" class="form-control py-2" id="email" name="email" placeholder="Enter email address">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="emailError"></p>
                </div>
            </div>

            <!-- Phone -->
            <div class="col-md-6">
                <label for="phone" class="form-label fw-semibold small text-dark mb-2">Phone Number <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-telephone text-secondary"></i>
                    </span>
                    <input type="tel" class="form-control py-2" id="phone" name="phone" placeholder="Enter phone number">
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="phoneError"></p>
                </div>
            </div>

            <!-- Gender -->
            <div class="col-md-6">
                <label for="gender" class="form-label fw-semibold small text-dark mb-2">Gender</label>
                <select class="form-select py-2" id="gender" name="gender">
                    <option value="" disabled selected>Select gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="genderError"></p>
                </div>
            </div>

            <!-- Joining Date -->
            <div class="col-md-6">
                <label for="joined_at" class="form-label fw-semibold small text-dark mb-2">Joining Date <span class="text-danger">*</span></label>
                <input type="text" class="form-control py-2 datepicker-input" id="joined_at" name="joined_at" value="{{ now()->toDateString() }}">
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="joined_atError"></p>
                </div>
            </div>

            <!-- Sport & Batch Assignments Container -->
            <div class="col-12 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">Sport & Batch Assignments</h6>
                    <button type="button" class="btn btn-dark btn-sm rounded-3 fw-semibold" id="add_assignment_btn">
                        <i class="bi bi-plus-circle me-1"></i> Add Sport/Batch
                    </button>
                </div>
                
                <div id="assignments_container">
                    <!-- Default Row 1 -->
                    <div class="card border border-secondary-subtle rounded-4 mb-3 p-3 assignment-row bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-secondary small">Assignment #<span class="row-num">1</span></span>
                            <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-assignment-btn d-none">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-dark mb-1">Sport <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm sport-select" name="assignments[0][sport_id]" required>
                                    <option value="" disabled selected>Select sport</option>
                                    @foreach ($sports as $sport)
                                        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-dark mb-1">Level <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm level-select" name="assignments[0][level_id]" required disabled>
                                    <option value="" disabled selected>Select sport first</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-dark mb-1">Batch <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm batch-select" name="assignments[0][batch_id]" required disabled>
                                    <option value="" disabled selected>Select level first</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-dark mb-1">Joined Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm datepicker-input joined-date-input" name="assignments[0][joined_at]" value="{{ now()->toDateString() }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="assignmentsError"></p>
                </div>
            </div>


            <!-- Submit Button -->
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">
                    <i class="bi bi-check-circle me-1"></i> Create Player
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Helper container for copying sport options via JS -->
<div id="sport_options_helper" style="display:none;">
    @foreach ($sports as $sport)
        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
    @endforeach
</div>
