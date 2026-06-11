<div class="container py-3">
    <form id="addPlayerFeeForm" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="url" value="{{ route('player-fees.store') }}">

        <!-- Player Select -->
        <div class="mb-3">
            <label for="player_id" class="form-label fw-semibold text-dark small">Select Player</label>
            <select name="player_id" id="player_id" class="form-select select2">
                <option value="">-- Choose Player --</option>
                @foreach ($players as $player)
                    <option value="{{ $player->id }}">{{ $player->firstname }} {{ $player->lastname }}
                        ({{ $player->phone }})
                    </option>
                @endforeach
            </select>
            <p class="text-danger small mb-0" id="player_idError"></p>
        </div>

        <!-- Enrolled Batches Section (Dynamic) -->
        <div id="playerBatchesSection" class="d-none"></div>

        <!-- Start & End Date -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="startDate" class="form-label fw-semibold text-dark small">Start Date</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">
                        <i class="bi bi-calendar-date"></i>
                    </span>
                    <input type="date" name="start_date" id="startDate" class="form-control border-start-0 ps-1"
                        required>
                </div>
                <p class="text-danger small mb-0" id="start_dateError"></p>
            </div>
            <div class="col-md-6">
                <label for="endDate" class="form-label fw-semibold text-dark small">End Date</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">
                        <i class="bi bi-calendar-date"></i>
                    </span>
                    <input type="date" name="end_date" id="endDate" class="form-control border-start-0 ps-1"
                        required>
                </div>
                <p class="text-danger small mb-0" id="end_dateError"></p>
            </div>
        </div>

        <!-- Calculated Duration Info -->
        <div class="mb-3">
            <div
                class="bg-light-subtle border border-secondary-subtle rounded-3 p-2 d-flex justify-content-between align-items-center">
                <span class="text-secondary small fw-semibold text-uppercase">Calculated Duration</span>
                <span class="fw-bold text-dark small" id="calculatedDuration">0 Month(s)</span>
            </div>
        </div>

        <!-- Calculations Fields -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Subtotal</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="sub_totalamount" id="sub_totalamount" class="form-control border-start-0 ps-1 fw-semibold text-dark" value="0.00">
                </div>
                <p class="text-danger small mb-0" id="sub_totalamountError"></p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Discount Applied</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount" class="form-control border-start-0 ps-1 fw-semibold text-success" value="0.00">
                </div>
                <p class="text-danger small mb-0" id="discount_amountError"></p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Total Amount</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="total_amt" id="total_amt" class="form-control border-start-0 ps-1 fw-bold text-primary" value="0.00">
                </div>
                <p class="text-danger small mb-0" id="total_amtError"></p>
            </div>
        </div>

        <!-- Payment Type -->
        <div class="mb-3">
            <label for="payment_type" class="form-label fw-semibold text-dark small">Payment Method</label>
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
                    Screenshot</label>
                <input type="file" name="img_upi" id="img_upi" class="form-control" accept="image/*">
                <p class="text-danger small mb-0" id="img_upiError"></p>
            </div>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="form-label fw-semibold text-dark small">Status</label>
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
