@php
    $start_month_val = '';
    $start_date_val = '';
    $end_date_val = '';
    if (!empty($preselected_month) && !empty($preselected_year)) {
        $start_month_val = sprintf('%04d-%02d', $preselected_year, $preselected_month);
        $start_date_val = sprintf('%04d-%02d-01', $preselected_year, $preselected_month);
        $end_date_val = \Carbon\Carbon::createFromDate($preselected_year, $preselected_month, 1)
            ->endOfMonth()
            ->format('Y-m-d');
    }
@endphp

<div class="container py-3">
    <form id="addPlayerFeeForm" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="url" value="{{ route('player-fees.store') }}">

        <!-- Player Select -->
        <div class="mb-3">
            <label for="player_id" class="form-label fw-semibold text-dark small">Select Player <span
                    class="text-danger">*</span></label>
            <select name="player_id" id="player_id" class="form-select select2">
                <option value="" disabled {{ !$preselected_player_id ? 'selected' : '' }}>-- Choose Player --
                </option>
                @foreach ($players as $player)
                    <option value="{{ $player->id }}" {{ $preselected_player_id == $player->id ? 'selected' : '' }}>
                        {{ $player->firstname }} {{ $player->lastname }}
                        ({{ $player->phone }})
                    </option>
                @endforeach
            </select>
            <p class="text-danger small mb-0" id="player_idError"></p>
        </div>

        <!-- Select Batch (Dynamic) -->
        <div class="mb-3 {{ $preselected_player_id ? '' : 'd-none' }}" id="batchSelectContainer">
            <label for="batch_id" class="form-label fw-semibold text-dark small">Select Batch <span
                    class="text-danger">*</span></label>
            <select name="batch_id" id="batch_id" class="form-select select2"
                data-preselected="{{ $preselected_batch_id }}">
                <option value="">-- Choose Batch --</option>
            </select>
            <p class="text-danger small mb-0" id="batch_idError"></p>
        </div>

        <!-- Start & End Month -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="startMonth" class="form-label fw-semibold text-dark small">Start Month <span
                        class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">
                        <i class="bi bi-calendar-date"></i>
                    </span>
                    <input type="text" id="startMonth" class="form-control border-start-0 ps-1 bg-white"
                        placeholder="Select Month" required readonly value="{{ $start_month_val }}">
                </div>
                <p class="text-danger small mb-0" id="start_dateError"></p>
            </div>
            <div class="col-md-6">
                <label for="endMonth" class="form-label fw-semibold text-dark small">End Month <span
                        class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">
                        <i class="bi bi-calendar-date"></i>
                    </span>
                    <input type="text" id="endMonth" class="form-control border-start-0 ps-1 bg-white"
                        placeholder="Select Month" required readonly value="{{ $start_month_val }}">
                </div>
                <p class="text-danger small mb-0" id="end_dateError"></p>
            </div>
        </div>

        <!-- Hidden actual date fields sent to backend -->
        <input type="hidden" name="start_date" id="startDate" value="{{ $start_date_val }}">
        <input type="hidden" name="end_date" id="endDate" value="{{ $end_date_val }}">

        <!-- Calculated Duration Info -->
        <div class="mb-3">
            <div
                class="bg-light-subtle border border-secondary-subtle rounded-3 p-2 d-flex justify-content-between align-items-center">
                <span class="text-secondary small fw-semibold text-uppercase">Calculated Duration </span>
                <span class="fw-bold text-dark small" id="calculatedDuration">0 Month(s)</span>
            </div>
        </div>

        <!-- Warning Alert for Overlapping Fees -->
        <div id="paymentOverlapWarning" class="alert alert-danger d-none py-2 px-3 mb-3 small fw-semibold"
            style="border-radius: 8px;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <span id="paymentOverlapWarningText"></span>
        </div>

        <!-- Warning Alert for Joined Date -->
        <div id="joinedDateWarning" class="alert alert-danger d-none py-2 px-3 mb-3 small fw-semibold"
            style="border-radius: 8px;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <span id="joinedDateWarningText"></span>
        </div>

        <!-- Calculations Fields -->
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Subtotal</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="sub_totalamount" id="sub_totalamount"
                        class="form-control border-start-0 ps-1 fw-semibold text-dark" value="0.00" readonly>
                </div>
                <p class="text-danger small mb-0" id="sub_totalamountError"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Penalty Amount <span class="text-danger"
                        id="penalty_num"></span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="penalty_amount" id="penalty_amount"
                        class="form-control border-start-0 ps-1 fw-semibold text-danger" value="0.00" readonly>
                </div>
                <p class="text-danger small mb-0" id="penalty_amountError"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Discount Applied</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount"
                        class="form-control border-start-0 ps-1 fw-semibold text-success" value="0.00">
                </div>
                <p class="text-danger small mb-0" id="discount_amountError"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Total Amount</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="total_amt" id="total_amt"
                        class="form-control border-start-0 ps-1 fw-bold text-primary" value="0.00" readonly>
                </div>
                <p class="text-danger small mb-0" id="total_amtError"></p>
            </div>
        </div>

        <!-- Payment Type -->
        <div class="mb-3">
            <label for="payment_type" class="form-label fw-semibold text-dark small">Payment Method <span
                    class="text-danger">*</span></label>
            <select name="payment_type" id="payment_type" class="form-select" required>
                <option value="cash" selected>Cash</option>
                <option value="card">Card</option>
                <option value="upi">UPI</option>
            </select>
            <p class="text-danger small mb-0" id="payment_typeError"></p>
        </div>

        <!-- UPI Details (Shown only if UPI selected) -->
        <div id="upiFields" class="d-none bg-light p-3 rounded-3 border mb-3">
            <div class="mb-3">
                <label for="upi_id" class="form-label fw-semibold text-dark small">UPI ID / Reference Number</label>
                <input type="text" name="upi_id" id="upi_id" class="form-control"
                    placeholder="e.g. name@upi or txn_12345">
                <p class="text-danger small mb-0" id="upi_idError"></p>
            </div>
            <div>
                <label for="img_upi" class="form-label fw-semibold text-dark small">Transaction Slip /
                    Screenshot <span class="text-danger">*</span></label>
                <input type="file" name="img_upi" id="img_upi" class="form-control" accept="image/*">
                <p class="text-danger small mb-0" id="img_upiError"></p>
            </div>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="form-label fw-semibold text-dark small">Status <span
                    class="text-danger">*</span></label>
            <select name="status" id="status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="paid" selected>Paid</option>
            </select>
            <p class="text-danger small mb-0" id="statusError"></p>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">
            <i class="bi bi-check-circle me-1"></i> Record Fee Payment
        </button>
    </form>
</div>
