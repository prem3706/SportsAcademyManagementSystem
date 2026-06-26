<x-layout>
    <div class="bg-body-tertiary vh-100 d-flex align-items-center overflow-hidden">

        <div class="container" style="max-width: 30rem;">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-4 p-md-4 bg-white rounded-4">

                    <!-- Heading -->
                    <div class="text-center mb-4">

                        <div class="bg-dark bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width:70px;height:70px;">

                            <i class="bi bi-person-fill fs-3"></i>

                        </div>

                        <h2 class="fw-bold mb-1 text-dark">
                            Login to your account
                        </h2>

                        <p class="text-secondary small mb-0">
                            Welcome back! Please sign in
                        </p>

                    </div>

                    <!-- Form -->
                    <form class="row g-3" action="{{ route('login') }}" method="POST" autocomplete="off">

                        @csrf

                        <!-- Email -->
                        <div class="col-12">

                            <label class="form-label small fw-semibold text-dark">
                                Email Address
                            </label>

                            <div class="input-group shadow-sm rounded-3">

                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="bi bi-envelope text-secondary"></i>
                                </span>

                                <input class="form-control border-0 py-2 @error('email') is-invalid @enderror"
                                    id="email" name="email" type="email" value="{{ old('email') }}"
                                    placeholder="your@email.com" autocomplete="off">

                            </div>

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Password -->
                        <div class="col-12">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <label class="form-label small fw-semibold text-dark mb-0">
                                    Password
                                </label>

                                <a href="{{ route('forget.password.get') }}" class="small fw-semibold">

                                    Forgot Password?

                                </a>

                            </div>

                            <div class="input-group shadow-sm rounded-3">

                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="bi bi-lock text-secondary"></i>
                                </span>

                                <input class="form-control border-0 py-2 @error('password') is-invalid @enderror"
                                    id="password" name="password" type="password" placeholder="Enter password"
                                    autocomplete="off">

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

                        <!-- Remember -->
                        <div class="col-12">

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" id="remember" name="remember">

                                <label class="form-check-label small text-secondary" for="remember">

                                    Remember me on this device

                                </label>

                            </div>

                        </div>

                        <!-- Button -->
                        <div class="col-12">

                            <button class="btn btn-dark w-100 py-2 fw-semibold rounded-3 shadow-sm" type="submit">

                                <i class="bi bi-box-arrow-in-right me-1"></i>

                                Sign In

                            </button>

                        </div>

                    </form>

                    <!-- Register -->
                    {{-- <div class="text-center mt-4 small text-secondary">

                        Need an account?

                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-dark">

                            Sign Up

                        </a>

                    </div> --}}

                </div>

            </div>

        </div>

    </div>


</x-layout>
