<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">
        <x-navbar />

        <div class="container-lg p-4">
            <!-- Header -->
            <div class="mb-4">
                <h4 class="fw-bold mb-1 text-dark">Fees Structure Settings</h4>
                <p class="text-secondary small mb-0">Configure penalty rules and prepayment discounts for player fees.
                </p>
            </div>

            <div class="row g-4">
                <!-- Penalty Settings Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 pb-2 px-4"
                            style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                            <h5 class="fw-bold mb-1 text-dark">Penalty Settings</h5>
                            <p class="text-secondary small mb-0">Set up charges for late payments</p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <form id="penaltySettingsForm">
                                @csrf
                                <input type="hidden" id="penalty_url" value="{{ route('settings.updatePenalty') }}">

                                <!-- Allow/Deny Penalty Toggle -->
                                <div class="bg-light p-3 d-flex align-items-center justify-content-between mb-4 border border-secondary-subtle"
                                    style="border-radius: 16px;">
                                    <div>
                                        <label class="form-check-label fw-bold text-dark mb-0" for="allow_penalty">
                                            Allow Late Fees Penalty
                                        </label>
                                        <p class="text-secondary small mb-0 mt-1">If enabled, penalty will be applied to
                                            unpaid invoices.</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            name="allow_penalty" id="allow_penalty" value="1"
                                            style="width: 2.5rem; height: 1.25rem; cursor: pointer;"
                                            {{ $settings->allow_penalty ? 'checked' : '' }}>
                                    </div>
                                </div>

                                <!-- Penalty Fields Wrapper -->
                                <div id="penaltyFieldsSection"
                                    class="transition-opacity {{ $settings->allow_penalty ? '' : 'disabled-section' }}">
                                    <!-- Penalty Days Input -->
                                    <div class="mb-3">
                                        <label for="penalty_days"
                                            class="form-label fw-semibold small text-dark mb-2">Penalty Grace
                                            Days</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white px-3 text-secondary border-end-0">
                                                <i class="bi bi-calendar-event"></i>
                                            </span>
                                            <input type="number" class="form-control py-2 border-start-0 ps-1"
                                                id="penalty_days" name="penalty_days"
                                                value="{{ $settings->penalty_days }}" min="0"
                                                placeholder="Enter grace days"
                                                {{ $settings->allow_penalty ? '' : 'disabled' }}>
                                            <span class="input-group-text bg-light text-secondary small">days after due
                                                date</span>
                                        </div>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="penalty_daysError"></p>
                                        </div>
                                    </div>

                                    <!-- Penalty Type Switch -->
                                    <div class="mb-3">
                                        <label for="penalty_type"
                                            class="form-label fw-semibold small text-dark mb-2">Penalty Charge
                                            Type</label>
                                        <select class="form-select py-2" id="penalty_type" name="penalty_type"
                                            {{ $settings->allow_penalty ? '' : 'disabled' }}>
                                            <option value="fixed"
                                                {{ $settings->penalty_type == 'fixed' ? 'selected' : '' }}>Fixed Amount
                                            </option>
                                            <option value="percentage"
                                                {{ $settings->penalty_type == 'percentage' ? 'selected' : '' }}>
                                                Percentage</option>
                                        </select>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="penalty_typeError"></p>
                                        </div>
                                    </div>

                                    <!-- Penalty Amount Input -->
                                    <div class="mb-4">
                                        <label for="penalty_amount"
                                            class="form-label fw-semibold small text-dark mb-2">Penalty Charge
                                            Value</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white px-3 text-secondary border-end-0"
                                                id="penaltyAmountIcon">
                                                {{ $settings->penalty_type == 'percentage' ? '%' : '₹' }}
                                            </span>
                                            <input type="number" step="0.01"
                                                class="form-control py-2 border-start-0 ps-1" id="penalty_amount"
                                                name="penalty_amount" value="{{ $settings->penalty_amount }}"
                                                min="0" placeholder="Enter amount"
                                                {{ $settings->allow_penalty ? '' : 'disabled' }}>
                                        </div>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="penalty_amountError"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Penalty Form Submit -->
                                <div class="mt-4 pt-3 border-top">
                                    <button type="submit"
                                        class="btn btn-dark w-100 py-2 fw-semibold rounded-3 btn-submit">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                            aria-hidden="true"></span>
                                        <i class="bi bi-check-circle me-1"></i> Save Penalty Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Discount Settings Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 pb-2 px-4"
                            style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                            <h5 class="fw-bold mb-1 text-dark">Prepayment Discount Settings</h5>
                            <p class="text-secondary small mb-0">Configure discounts for prepay packages</p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <form id="discountSettingsForm">
                                @csrf
                                <input type="hidden" id="discount_url" value="{{ route('settings.updateDiscount') }}">

                                <p class="text-secondary small mb-4">Specify discount values and charge types for
                                    players prepaying
                                    their fees across different durations.</p>

                                <div class="row g-3 mb-3">
                                    <!-- Discount Type Switch -->
                                    <div class="col-12 mb-3">
                                        <label for="discount_type"
                                            class="form-label fw-semibold small text-dark mb-2">Discount Charge
                                            Type</label>
                                        <select class="form-select py-2" id="discount_type" name="discount_type">
                                            <option value="percentage"
                                                {{ $settings->discount_type == 'percentage' ? 'selected' : '' }}>
                                                Percentage</option>
                                            <option value="fixed"
                                                {{ $settings->discount_type == 'fixed' ? 'selected' : '' }}>Fixed
                                                Amount
                                            </option>
                                        </select>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="discount_typeError"></p>
                                        </div>
                                    </div>

                                    <!-- Monthly Discount -->
                                    <div class="col-md-6">
                                        <label for="discount_monthly"
                                            class="form-label fw-semibold small text-dark mb-2">Monthly Package
                                            (<span
                                                class="discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white px-3 text-secondary border-end-0">
                                                <i class="bi bi-calendar"></i>
                                            </span>
                                            <input type="number" step="0.01"
                                                class="form-control py-2 border-start-0 ps-1" id="discount_monthly"
                                                name="discount_monthly" value="{{ $settings->discount_monthly }}"
                                                min="0"
                                                {{ $settings->discount_type == 'percentage' ? 'max=100' : '' }}
                                                placeholder="0.00">
                                            <span
                                                class="input-group-text bg-light text-secondary small discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>
                                        </div>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="discount_monthlyError"></p>
                                        </div>
                                    </div>

                                    <!-- Quarterly Discount -->
                                    <div class="col-md-6">
                                        <label for="discount_quarterly"
                                            class="form-label fw-semibold small text-dark mb-2">Quarterly (3 Months)
                                            (<span
                                                class="discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white px-3 text-secondary border-end-0">
                                                <i class="bi bi-calendar-range"></i>
                                            </span>
                                            <input type="number" step="0.01"
                                                class="form-control py-2 border-start-0 ps-1" id="discount_quarterly"
                                                name="discount_quarterly" value="{{ $settings->discount_quarterly }}"
                                                min="0"
                                                {{ $settings->discount_type == 'percentage' ? 'max=100' : '' }}
                                                placeholder="0.00">
                                            <span
                                                class="input-group-text bg-light text-secondary small discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>
                                        </div>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="discount_quarterlyError"></p>
                                        </div>
                                    </div>

                                    <!-- Half-Yearly Discount -->
                                    <div class="col-md-6">
                                        <label for="discount_half_yearly"
                                            class="form-label fw-semibold small text-dark mb-2">Half-Yearly (6 Months)
                                            (<span
                                                class="discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white px-3 text-secondary border-end-0">
                                                <i class="bi bi-calendar2-month"></i>
                                            </span>
                                            <input type="number" step="0.01"
                                                class="form-control py-2 border-start-0 ps-1"
                                                id="discount_half_yearly" name="discount_half_yearly"
                                                value="{{ $settings->discount_half_yearly }}" min="0"
                                                {{ $settings->discount_type == 'percentage' ? 'max=100' : '' }}
                                                placeholder="0.00">
                                            <span
                                                class="input-group-text bg-light text-secondary small discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>
                                        </div>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="discount_half_yearlyError"></p>
                                        </div>
                                    </div>

                                    <!-- Yearly Discount -->
                                    <div class="col-md-6">
                                        <label for="discount_yearly"
                                            class="form-label fw-semibold small text-dark mb-2">Yearly (12 Months)
                                            (<span
                                                class="discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white px-3 text-secondary border-end-0">
                                                <i class="bi bi-calendar3"></i>
                                            </span>
                                            <input type="number" step="0.01"
                                                class="form-control py-2 border-start-0 ps-1" id="discount_yearly"
                                                name="discount_yearly" value="{{ $settings->discount_yearly }}"
                                                min="0"
                                                {{ $settings->discount_type == 'percentage' ? 'max=100' : '' }}
                                                placeholder="0.00">
                                            <span
                                                class="input-group-text bg-light text-secondary small discount-type-symbol">{{ $settings->discount_type == 'fixed' ? '₹' : '%' }}</span>
                                        </div>
                                        <div style="height:15px;">
                                            <p class="text-danger small mb-0" id="discount_yearlyError"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Discount Form Submit -->
                                <div class="mt-4 pt-3 border-top">
                                    <button type="submit"
                                        class="btn btn-dark w-100 py-2 fw-semibold rounded-3 btn-submit">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                            aria-hidden="true"></span>
                                        <i class="bi bi-check-circle me-1"></i> Save Discount Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive styling transitions -->
    <style>
        .transition-opacity {
            transition: opacity 0.3s ease, transform 0.3s ease;
            opacity: 1;
            transform: translateY(0);
        }

        .disabled-section {
            opacity: 0.45;
            pointer-events: none;
            transform: translateY(-5px);
        }
    </style>
</x-layout>
