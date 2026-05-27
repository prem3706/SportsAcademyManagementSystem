<x-layout>

    <div class="bg-body-tertiary min-vh-100 d-flex align-items-center">

        <div class="container" style="max-width: 30rem;">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-4 p-md-4 bg-white rounded-4">

                    <!-- Heading -->
                    <div class="text-center mb-4">

                        <div class="bg-dark bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width:70px;height:70px;">

                            <i class="bi bi-envelope-paper-fill fs-3"></i>

                        </div>

                        <h2 class="fw-bold mb-1 text-dark">
                            Reset Password
                        </h2>

                        <p class="text-secondary small mb-0">
                            Enter your email address and we will send reset instructions
                        </p>

                    </div>

                    <!-- Form -->
                    <form class="row g-3" action="{{ route('forget.password.post') }}" method="post">
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

                                <input class="form-control border-0 py-2" id="email" name="email" type="email"
                                    placeholder="your@email.com" autocomplete="off">

                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <!-- Button -->
                        <div class="col-12">

                            <button class="btn btn-dark w-100 py-2 fw-semibold rounded-3 shadow-sm" type="submit">

                                <i class="bi bi-send me-1"></i>

                                Send Reset Instructions

                            </button>

                        </div>

                    </form>

                    <!-- Back Login -->
                    <div class="text-center mt-4 small text-secondary">

                        Remember your password?

                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-dark">

                            Sign In

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-layout>
