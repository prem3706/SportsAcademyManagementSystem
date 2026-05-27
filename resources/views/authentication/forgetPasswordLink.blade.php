<x-layout>

    <div class="bg-body-tertiary min-vh-100 d-flex align-items-center">

        <div class="container" style="max-width: 32rem;">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-4 p-md-4 bg-white rounded-4">

                    <!-- Heading -->
                    <div class="text-center mb-4">

                        <div class="bg-dark bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width:70px;height:70px;">

                            <i class="bi bi-shield-lock-fill fs-3"></i>

                        </div>

                        <h2 class="fw-bold mb-1 text-dark">
                            Reset Password
                        </h2>

                        <p class="text-secondary small mb-0">
                            Enter your new password below
                        </p>

                    </div>

                    <!-- Success Message -->
                    @if (session('message'))
                        <div class="alert alert-success rounded-3">
                            {{ session('message') }}
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger rounded-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form class="row g-3" action="{{ route('reset.password.post') }}" method="POST">

                        @csrf

                        <!-- Hidden Token -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email -->
                        <div class="col-12">

                            <label class="form-label small fw-semibold text-dark">
                                Email Address
                            </label>

                            <div class="input-group shadow-sm rounded-3">

                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="bi bi-envelope text-secondary"></i>
                                </span>

                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter your email"
                                    class="form-control border-0 py-2 @error('email') is-invalid @enderror">

                            </div>

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Password -->
                        <div class="col-12">

                            <label class="form-label small fw-semibold text-dark">
                                New Password
                            </label>

                            <div class="input-group shadow-sm rounded-3">

                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="bi bi-lock text-secondary"></i>
                                </span>

                                <input id="password" type="password" name="password" placeholder="Enter new password"
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
                        <div class="col-12">

                            <label class="form-label small fw-semibold text-dark">
                                Confirm Password
                            </label>

                            <div class="input-group shadow-sm rounded-3">

                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="bi bi-shield-lock text-secondary"></i>
                                </span>

                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="Confirm password"
                                    class="form-control border-0 py-2 @error('password_confirmation') is-invalid @enderror">

                                <span class="input-group-text bg-white border-0" id="toggleConfirmPassword"
                                    style="cursor:pointer;">

                                    <i class="bi bi-eye-slash text-secondary" id="toggleConfirmIcon"></i>

                                </span>

                            </div>

                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Submit -->
                        <div class="col-12">

                            <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3 shadow-sm">

                                <i class="bi bi-check-circle me-1"></i>

                                Reset Password

                            </button>

                        </div>

                    </form>

                    <!-- Back Login -->
                    <div class="text-center mt-4 small text-secondary">

                        Back to

                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-dark">

                            Sign In

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>



</x-layout>
