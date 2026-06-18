<div class="d-flex justify-content-center">
    <form method="POST" id="editUserForm" style="max-width: 950px; width:100%;">

        @csrf
        @method('PUT')

        <input type="hidden" name="url" id="url" value="{{ route('users.update', $user->id) }}">
        {{-- <input type="hidden" name="userId" id="userId"> --}}
        <div class="row g-3">

            <!-- First Name -->
            <div class="col-md-6">

                <label for="firstname" class="form-label fw-semibold small text-dark mb-2">
                    First Name <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-person text-secondary"></i>
                    </span>

                    <input type="text" class="form-control py-2" id="firstname" name="firstname"
                        value="{{ $user->firstname }}" placeholder="Enter first name">
                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="firstnameError"></p>
                </div>

            </div>

            <!-- Last Name -->
            <div class="col-md-6">

                <label for="lastname" class="form-label fw-semibold small text-dark mb-2">
                    Last Name <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-person text-secondary"></i>
                    </span>

                    <input type="text" class="form-control py-2" id="lastname" name="lastname"
                        value="{{ $user->lastname }}" placeholder="Enter last name">
                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="lastnameError"></p>
                </div>

            </div>

            <!-- Email -->
            <div class="col-md-6">

                <label for="email" class="form-label fw-semibold small text-dark mb-2">
                    Email Address
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-envelope text-secondary"></i>
                    </span>

                    <input type="email" class="form-control py-2" id="email" name="email"
                        value="{{ $user->email }}" placeholder="Enter email address">
                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="emailError"></p>
                </div>

            </div>

            <!-- Phone -->
            <div class="col-md-6">

                <label for="phone" class="form-label fw-semibold small text-dark mb-2">
                    Phone Number <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-telephone text-secondary"></i>
                    </span>

                    <input type="tel" class="form-control py-2" id="phone" name="phone"
                        value="{{ $user->phone }}" placeholder="Enter phone number">
                </div>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="phoneError"></p>
                </div>

            </div>

            <!-- Password -->
            <div class="col-md-6">

                <label for="password" class="form-label fw-semibold small text-dark mb-2">
                    Password
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-white px-3">
                        <i class="bi bi-lock text-secondary"></i>
                    </span>

                    <input type="password" class="form-control py-2" id="password" name="password"
                        placeholder="Enter password">
                </div>

                <div style="height:10px;">
                    {{-- <p class="text-danger small mb-0" id="passwordDescription"></p> --}}
                    <p class="text-danger small mb-0" id="passwordError">Your password will be changed if you
                        enter a new one.</p>
                </div>

            </div>

            <!-- Role -->
            <div class="col-md-6">

                <label for="role" class="form-label fw-semibold small text-dark mb-2">
                    Role <span class="text-danger">*</span>
                </label>

                <select class="form-select py-2" id="role" name="role">

                    <option value="" disabled selected>
                        Select role
                    </option>

                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="coach" {{ $user->role == 'coach' ? 'selected' : '' }}>Coach</option>
                    <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>

                </select>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="roleError"></p>
                </div>

            </div>

            <!-- Gender -->
            <div class="col-md-6">

                <label for="gender" class="form-label fw-semibold small text-dark mb-2">
                    Gender <span class="text-danger">*</span>
                </label>

                <select class="form-select py-2" id="gender" name="gender">

                    <option value="" disabled selected>
                        Select gender
                    </option>

                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>

                </select>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="genderError"></p>
                </div>

            </div>

            <!-- Joining Date -->
            <div class="col-md-6" id="joining_date_div">

                <label for="joined_at" class="form-label fw-semibold small text-dark mb-2">
                    Joining Date
                </label>

                <input type="date" class="form-control py-2" id="joined_at" name="joined_at"
                    value="{{ $user->joined_at }}" placeholder="Select joining date">

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="joined_atError"></p>
                </div>

            </div>

            <!-- Status -->
            <div class="col-12">

                <label for="status" class="form-label fw-semibold small text-dark mb-2">
                    Status <span class="text-danger">*</span>
                </label>

                <select class="form-select py-2" id="status" name="status">

                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>

                </select>

                <div style="height:10px;">
                    <p class="text-danger small mb-0" id="statusError"></p>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-2">

                <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">

                    <i class="bi bi-check-circle me-1"></i>

                    Update User

                </button>

            </div>

        </div>

    </form>
</div>
