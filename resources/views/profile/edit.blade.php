<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">
        <x-navbar />

        <div class="container-lg p-4">
            <!-- Header & Breadcrumbs -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">My Profile</h4>
                    <p class="text-secondary small mb-0">View and update your personal information and account settings.
                    </p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>

            <!-- Profile Content -->
            <div class="row g-4 mb-5">
                <!-- Left Side Card: Avatar & Stats (Sidebar Color Matching) -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm overflow-hidden h-100 text-white"
                        style="border-radius: 16px; background-color: #212631;">
                        <!-- Dark Sidebar-Matching Header -->
                        <div class="p-4 text-center position-relative" style="background-color: #212631;">
                            <div class="position-absolute top-0 end-0 p-2">
                                <span
                                    class="badge bg-success border border-light rounded-pill px-2.5 py-1 text-uppercase fw-semibold"
                                    style="font-size: 10px; letter-spacing: 0.5px;">
                                    {{ $user->status ?? 'active' }}
                                </span>
                            </div>

                            <!-- Avatar Wrapper -->
                            <div class="mt-3 mb-3 position-relative d-inline-block">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('assets/img/avatars/default.jpg') }}"
                                    alt="{{ $user->email }}" class="rounded-circle border border-4 border-white"
                                    style="width: 110px; height: 110px; object-fit: cover;">
                            </div>

                            <h5 class="fw-bold text-white mb-1">{{ $user->firstname }} {{ $user->lastname }}</h5>
                            <p class="text-white-50 small mb-0 text-uppercase fw-semibold tracking-wider"
                                style="font-size: 11px; letter-spacing: 1px;">
                                <i class="bi bi-shield-lock me-1"></i>{{ $user->roles->first()->name ?? 'User' }}
                            </p>
                        </div>

                        <!-- Account Details List -->
                        <div class="card-body p-4" style="background-color: #212631;">
                            <h6 class="fw-bold text-white mb-3">Account Details</h6>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-3 p-2.5 me-3 text-white-50" style="background-color: #2d3748;">
                                    <i class="bi bi-envelope-fill" style="font-size: 16px;"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-white-50 mb-0 small" style="font-size: 11px; opacity: 0.7;">Email
                                        Address</p>
                                    <p class="fw-semibold text-white mb-0 text-truncate" style="font-size: 13.5px;">
                                        {{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-3 p-2.5 me-3 text-white-50" style="background-color: #2d3748;">
                                    <i class="bi bi-telephone-fill" style="font-size: 16px;"></i>
                                </div>
                                <div>
                                    <p class="text-white-50 mb-0 small" style="font-size: 11px; opacity: 0.7;">Phone
                                        Number</p>
                                    <p class="fw-semibold text-white mb-0" style="font-size: 13.5px;">
                                        {{ $user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-3 p-2.5 me-3 text-white-50" style="background-color: #2d3748;">
                                    <i class="bi bi-gender-ambiguous" style="font-size: 16px;"></i>
                                </div>
                                <div>
                                    <p class="text-white-50 mb-0 small" style="font-size: 11px; opacity: 0.7;">Gender
                                    </p>
                                    <p class="fw-semibold text-white mb-0 text-capitalize" style="font-size: 13.5px;">
                                        {{ $user->gender ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="rounded-3 p-2.5 me-3 text-white-50" style="background-color: #2d3748;">
                                    <i class="bi bi-calendar-check-fill" style="font-size: 16px;"></i>
                                </div>
                                <div>
                                    <p class="text-white-50 mb-0 small" style="font-size: 11px; opacity: 0.7;">Joined
                                        Date</p>
                                    <p class="fw-semibold text-white mb-0" style="font-size: 13.5px;">
                                        {{ $user->joined_at ? \Carbon\Carbon::parse($user->joined_at)->format('d M, Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side Form: Edit Profile Details -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                        <!-- Tab Header -->
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <ul class="nav nav-pills card-header-pills bg-light p-1 rounded-3" id="profileTabs"
                                role="tablist">
                                <li class="nav-item" role="presentation" style="flex: 1;">
                                    <button
                                        class="nav-link w-100 active py-2.5 fw-semibold rounded-3 d-flex align-items-center justify-content-center"
                                        id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button"
                                        role="tab" aria-controls="info-pane" aria-selected="true">
                                        <i class="bi bi-person-fill me-2"></i>Profile Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation" style="flex: 1;">
                                    <button
                                        class="nav-link w-100 py-2.5 fw-semibold rounded-3 d-flex align-items-center justify-content-center"
                                        id="security-tab" data-bs-toggle="tab" data-bs-target="#security-pane"
                                        type="button" role="tab" aria-controls="security-pane"
                                        aria-selected="false">
                                        <i class="bi bi-shield-lock-fill me-2"></i>Password & Security
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Form Start -->
                        <form id="profileForm" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body p-4">
                                <div class="tab-content" id="profileTabContent">

                                    <!-- Tab 1: Profile Details -->
                                    <div class="tab-pane fade show active" id="info-pane" role="tabpanel"
                                        aria-labelledby="info-tab" tabindex="0">
                                        <div class="row g-3">
                                            <!-- Profile Picture Upload -->
                                            <div class="col-12 mb-2">
                                                <label class="form-label fw-bold small text-dark mb-2">
                                                    Update Profile Picture
                                                </label>
                                                <div class="profile-pic-upload-container">
                                                    <input type="hidden" name="remove_profile_picture"
                                                        id="remove_profile_picture" value="0">
                                                    <input type="file" name="profile_picture" id="profile_picture"
                                                        class="dropify"
                                                        data-default-file="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('assets/img/avatars/default.jpg') }}"
                                                        data-allowed-file-extensions="jpeg jpg png webp"
                                                        data-max-file-size="2M" />
                                                </div>
                                                <p class="text-danger small mb-0 mt-1" id="profile_pictureError"></p>
                                            </div>

                                            <!-- First Name -->
                                            <div class="col-md-6">
                                                <label for="firstname"
                                                    class="form-label fw-semibold small text-dark mb-2">
                                                    First Name <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span
                                                        class="input-group-text bg-white px-3 border-end-0 text-secondary">
                                                        <i class="bi bi-person"></i>
                                                    </span>
                                                    <input type="text"
                                                        class="form-control py-2 border-start-0 ps-1" id="firstname"
                                                        name="firstname" value="{{ $user->firstname }}"
                                                        placeholder="Enter first name">
                                                </div>
                                                <p class="text-danger small mb-0 mt-1" id="firstnameError"></p>
                                            </div>

                                            <!-- Last Name -->
                                            <div class="col-md-6">
                                                <label for="lastname"
                                                    class="form-label fw-semibold small text-dark mb-2">
                                                    Last Name <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span
                                                        class="input-group-text bg-white px-3 border-end-0 text-secondary">
                                                        <i class="bi bi-person"></i>
                                                    </span>
                                                    <input type="text"
                                                        class="form-control py-2 border-start-0 ps-1" id="lastname"
                                                        name="lastname" value="{{ $user->lastname }}"
                                                        placeholder="Enter last name">
                                                </div>
                                                <p class="text-danger small mb-0 mt-1" id="lastnameError"></p>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-md-6">
                                                <label for="email"
                                                    class="form-label fw-semibold small text-dark mb-2">
                                                    Email Address <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span
                                                        class="input-group-text bg-white px-3 border-end-0 text-secondary">
                                                        <i class="bi bi-envelope"></i>
                                                    </span>
                                                    <input type="email"
                                                        class="form-control py-2 border-start-0 ps-1" id="email"
                                                        name="email" value="{{ $user->email }}"
                                                        placeholder="Enter email address">
                                                </div>
                                                <p class="text-danger small mb-0 mt-1" id="emailError"></p>
                                            </div>

                                            <!-- Phone -->
                                            <div class="col-md-6">
                                                <label for="phone"
                                                    class="form-label fw-semibold small text-dark mb-2">
                                                    Phone Number <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span
                                                        class="input-group-text bg-white px-3 border-end-0 text-secondary">
                                                        <i class="bi bi-telephone"></i>
                                                    </span>
                                                    <input type="text"
                                                        class="form-control py-2 border-start-0 ps-1" id="phone"
                                                        name="phone" value="{{ $user->phone }}"
                                                        placeholder="Enter 10-digit phone number">
                                                </div>
                                                <p class="text-danger small mb-0 mt-1" id="phoneError"></p>
                                            </div>

                                            <!-- Gender -->
                                            <div class="col-md-12">
                                                <label for="gender"
                                                    class="form-label fw-semibold small text-dark mb-2">
                                                    Gender <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select py-2" id="gender" name="gender">
                                                    <option value="" disabled>Select Gender</option>
                                                    <option value="male"
                                                        {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female"
                                                        {{ $user->gender == 'female' ? 'selected' : '' }}>Female
                                                    </option>
                                                    <option value="other"
                                                        {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                <p class="text-danger small mb-0 mt-1" id="genderError"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab 2: Security & Password -->
                                    <div class="tab-pane fade" id="security-pane" role="tabpanel"
                                        aria-labelledby="security-tab" tabindex="0">
                                        <div class="row g-3">
                                            <!-- Alert Box -->
                                            <div class="col-12">
                                                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-3 p-3 rounded-3"
                                                    role="alert"
                                                    style="background-color: #fff9e6; border-left: 4px solid #ffc107 !important; color: #664d03;">
                                                    <i
                                                        class="bi bi-exclamation-triangle-fill fs-5 me-3 text-warning"></i>
                                                    <div class="small">
                                                        Leave password fields blank if you do not wish to change your
                                                        current password.
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- New Password -->
                                            <div class="col-md-6">
                                                <label for="password"
                                                    class="form-label fw-semibold small text-dark mb-2">
                                                    New Password
                                                </label>
                                                <div class="input-group">
                                                    <span
                                                        class="input-group-text bg-white px-3 border-end-0 text-secondary">
                                                        <i class="bi bi-lock"></i>
                                                    </span>
                                                    <input type="password" class="form-control py-2 border-x-0 ps-1"
                                                        id="password" name="password"
                                                        placeholder="Enter new password">
                                                    <button
                                                        class="btn btn-outline-secondary border-start-0 bg-white toggle-password text-secondary px-3"
                                                        type="button" style="border-color: #dee2e6;">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                </div>
                                                <p class="text-danger small mb-0 mt-1" id="passwordError"></p>
                                            </div>

                                            <!-- Confirm Password -->
                                            <div class="col-md-6">
                                                <label for="password_confirmation"
                                                    class="form-label fw-semibold small text-dark mb-2">
                                                    Confirm New Password
                                                </label>
                                                <div class="input-group">
                                                    <span
                                                        class="input-group-text bg-white px-3 border-end-0 text-secondary">
                                                        <i class="bi bi-lock-fill"></i>
                                                    </span>
                                                    <input type="password" class="form-control py-2 border-x-0 ps-1"
                                                        id="password_confirmation" name="password_confirmation"
                                                        placeholder="Confirm new password">
                                                    <button
                                                        class="btn btn-outline-secondary border-start-0 bg-white toggle-password text-secondary px-3"
                                                        type="button" style="border-color: #dee2e6;">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                </div>
                                                <p class="text-danger small mb-0 mt-1"
                                                    id="password_confirmationError"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer: Save Changes -->
                            <div class="card-footer bg-white border-0 pb-4 px-4 pt-0 d-flex justify-content-end">
                                <button type="submit"
                                    class="btn btn-primary px-4 py-2.5 fw-semibold rounded-3 shadow-sm d-flex align-items-center justify-content-center btn-submit"
                                    style="min-width: 150px; background-color: #212631; border-color: #212631; transition: all 0.2s ease;">
                                    <i class="bi bi-check2-circle me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styling for dropify adjustments -->
    <style>
        .profile-pic-upload-container .dropify-wrapper {
            border-radius: 12px;
            border: 2px dashed #dee2e6;
            transition: border-color 0.25s ease;
        }

        .profile-pic-upload-container .dropify-wrapper:hover {
            border-color: #212631;
        }

        .nav-pills .nav-link {
            color: #495057;
            background-color: transparent;
            transition: all 0.2s ease;
        }

        .nav-pills .nav-link.active {
            color: #fff;
            background-color: #212631;
            box-shadow: 0 4px 6px rgba(33, 38, 49, 0.2);
        }

        .breadcrumb-item a {
            color: #212631;
        }

        .input-group:focus-within .input-group-text {
            border-color: #86b7fe;
            color: #212631 !important;
        }

        .btn-submit:hover {
            background-color: #1a1d24 !important;
            border-color: #1a1d24 !important;
        }

        .btn-submit:focus {
            box-shadow: 0 0 0 3px rgba(33, 38, 49, 0.25) !important;
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Dropify
                var drEvent = $('.dropify').dropify({
                    messages: {
                        'default': 'Drag and drop your avatar here or click',
                        'replace': 'Drag and drop or click to replace',
                        'remove': 'Remove',
                        'error': 'Ooops, something wrong appended.'
                    }
                });

                // Handle file clear (remove)
                drEvent.on('dropify.afterClear', function(event, element) {
                    $('#remove_profile_picture').val('1');
                });

                // Reset remove flag if new file is selected
                $('#profile_picture').on('change', function() {
                    if (this.files && this.files.length > 0) {
                        $('#remove_profile_picture').val('0');
                    }
                });

                // Toggle Password Visibility
                $('.toggle-password').on('click', function() {
                    var input = $(this).closest('.input-group').find('input');
                    var icon = $(this).find('i');
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('bi-eye-slash').addClass('bi-eye');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('bi-eye').addClass('bi-eye-slash');
                    }
                });

                // Form Submit with AJAX
                $('#profileForm').on('submit', function(e) {
                    e.preventDefault();

                    // Reset styling
                    $('.text-danger').text('');
                    $('input, select').removeClass('is-invalid');
                    $('.input-group').removeClass('border-danger');

                    var submitBtn = $(this).find('button[type="submit"]');
                    var originalBtnHtml = submitBtn.html();
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...'
                    );

                    var formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('profile.update') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            submitBtn.prop('disabled', false).html(originalBtnHtml);
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            submitBtn.prop('disabled', false).html(originalBtnHtml);
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                var firstErrorTab = null;

                                $.each(errors, function(key, val) {
                                    // Highlight fields and set text error
                                    $('#' + key + 'Error').text(val[0]);
                                    $('#' + key).addClass('is-invalid');

                                    // Determine which tab the error belongs to
                                    var fieldInput = $('#' + key);
                                    if (fieldInput.length > 0) {
                                        var tabPane = fieldInput.closest('.tab-pane');
                                        if (tabPane.length > 0 && !firstErrorTab) {
                                            firstErrorTab = tabPane.attr('id');
                                        }
                                    }
                                });

                                // Automatically switch to tab containing the first validation error
                                if (firstErrorTab) {
                                    var tabTrigger = $('button[data-bs-target="#' + firstErrorTab +
                                        '"]');
                                    if (tabTrigger.length > 0) {
                                        var tab = new bootstrap.Tab(tabTrigger[0]);
                                        tab.show();
                                    }
                                }

                                toastr.error('Please correct the validation errors before saving.');
                            } else {
                                toastr.error('An error occurred. Please try again later.');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layout>
