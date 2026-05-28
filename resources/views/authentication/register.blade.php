<x-layout>
    <div class="bg-body-tertiary min-vh-100 d-flex align-items-center py-3">

        <div class="container-fluid px-3">

            <div class="row justify-content-center">

                <!-- Width Increased -->
                <div class="col-12 col-xl-9 col-xxl-8">

                    <div class="card border-0 shadow-lg rounded-4">

                        <div class="card-body p-4 p-md-4 bg-white rounded-4">

                            <!-- Heading -->
                            <div class="text-center mb-4">

                                <h2 class="fw-bold mb-1 fs-3 text-dark">
                                    Create New Account
                                </h2>

                                <p class="text-secondary small mb-0">
                                    Fill in your details to continue
                                </p>

                            </div>

                            <!-- Form -->
                            <form class="row g-3" action="{{ route('register') }}" method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <!-- First Name -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        First Name
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-person text-secondary"></i>
                                        </span>

                                        <input type="text" name="firstname" value="{{ old('firstname') }}"
                                            placeholder="First name"
                                            class="form-control border-0 py-2 @error('firstname') is-invalid @enderror">

                                    </div>

                                    @error('firstname')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Last Name
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-person text-secondary"></i>
                                        </span>

                                        <input type="text" name="lastname" value="{{ old('lastname') }}"
                                            placeholder="Last name"
                                            class="form-control border-0 py-2 @error('lastname') is-invalid @enderror">

                                    </div>

                                    @error('lastname')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Email -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Email Address
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-envelope text-secondary"></i>
                                        </span>

                                        <input type="email" name="email" value="{{ old('email') }}"
                                            placeholder="your@email.com"
                                            class="form-control border-0 py-2 @error('email') is-invalid @enderror">

                                    </div>

                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Phone Number
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-telephone text-secondary"></i>
                                        </span>

                                        <input type="tel" name="phone" value="{{ old('phone') }}"
                                            placeholder="Phone number"
                                            class="form-control border-0 py-2 @error('phone') is-invalid @enderror">

                                    </div>

                                    @error('phone')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Gender
                                    </label>

                                    <div class="shadow-sm rounded-3">

                                        <select name="gender"
                                            class="form-select border-0 py-2 @error('gender') is-invalid @enderror">

                                            <option value="">
                                                Select gender
                                            </option>

                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                Male
                                            </option>

                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                Female
                                            </option>

                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                                                Other
                                            </option>

                                        </select>

                                    </div>

                                    @error('gender')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Profile Picture -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Profile Picture
                                    </label>

                                    <div class="shadow-sm rounded-3">

                                        <input type="file" name="profile_picture"
                                            class="form-control border-0 py-2 @error('profile_picture') is-invalid @enderror">

                                    </div>

                                    @error('profile_picture')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Password -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Password
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-lock text-secondary"></i>
                                        </span>

                                        <input id="password" type="password" name="password"
                                            placeholder="Enter password"
                                            class="form-control border-0 py-2 @error('password') is-invalid @enderror">

                                        <span class="input-group-text bg-white border-0" id="togglePassword"
                                            style="cursor:pointer;">

                                            <i class="bi bi-eye-slash text-secondary" id="toggleIcon"></i>

                                        </span>

                                    </div>

                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">

                                    <label class="form-label small fw-semibold text-dark">
                                        Confirm Password
                                    </label>

                                    <div class="input-group shadow-sm rounded-3">

                                        <span class="input-group-text bg-white border-0">
                                            <i class="bi bi-shield-lock text-secondary"></i>
                                        </span>

                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                            placeholder="Confirm password" class="form-control border-0 py-2">

                                    </div>

                                </div>

                                <!-- Terms -->
                                <div class="col-12">

                                    <div class="form-check">

                                        <input type="checkbox" name="terms" class="form-check-input">

                                        <label class="form-check-label small text-secondary">

                                            I accept the

                                            <a href="#" class="text-decoration-none fw-semibold text-dark">

                                                terms & conditions

                                            </a>

                                        </label>

                                    </div>

                                </div>

                                <!-- Submit -->
                                <div class="col-12">

                                    <button type="submit"
                                        class="btn btn-dark w-100 py-2 rounded-3 fw-semibold shadow-sm">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Create Account

                                    </button>

                                </div>

                            </form>

                            <!-- Login -->
                            <div class="text-center mt-4 small text-secondary">

                                Already have an account?

                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-dark">

                                    Sign In

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</x-layout>
