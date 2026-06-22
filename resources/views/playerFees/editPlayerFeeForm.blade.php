<div class="container py-3">
    <form id="editPlayerFeeForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" id="url" value="{{ route('player-fees.update', $playerFee->id) }}">
        <input type="hidden" id="selected_player_id" value="{{ $playerFee->player_id }}">
        <input type="hidden" id="batch_fee" value="{{ $batchFee }}">

        <!-- Player (Read Only) -->
        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary small">Player</label>
            <input type="text" class="form-control bg-light"
                value="{{ $playerFee->player->firstname }} {{ $playerFee->player->lastname }} ({{ $playerFee->player->phone }})"
                readonly>
            <input type="hidden" name="player_id" value="{{ $playerFee->player_id }}">
        </div>

        <!-- Batch (Read Only) -->
        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary small">Batch</label>
            <input type="text" class="form-control bg-light"
                value="{{ $playerFee->batch ? $playerFee->batch->name . ' (' . ($playerFee->batch->sport->name ?? '') . ' - ' . ($playerFee->batch->level->name ?? '') . ')' : 'N/A' }}"
                readonly>
            <input type="hidden" name="batch_id" value="{{ $playerFee->batch_id }}">
            <p class="text-danger small mb-0" id="batch_idError"></p>
        </div>

        <!-- Start & End Month -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="startMonthEdit" class="form-label fw-semibold text-dark small">Start Month</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">
                        <i class="bi bi-calendar-date"></i>
                    </span>
                    <input type="text" id="startMonthEdit" class="form-control border-start-0 ps-1 bg-white"
                        value="{{ $playerFee->start_date ? $playerFee->start_date->format('Y-m') : '' }}"
                        placeholder="Select Month" required readonly>
                </div>
                <p class="text-danger small mb-0" id="start_dateError"></p>
            </div>
            <div class="col-md-6">
                <label for="endMonthEdit" class="form-label fw-semibold text-dark small">End Month</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">
                        <i class="bi bi-calendar-date"></i>
                    </span>
                    <input type="text" id="endMonthEdit" class="form-control border-start-0 ps-1 bg-white"
                        value="{{ $playerFee->end_date ? $playerFee->end_date->format('Y-m') : '' }}"
                        placeholder="Select Month" required readonly>
                </div>
                <p class="text-danger small mb-0" id="end_dateError"></p>
            </div>
        </div>

        <!-- Hidden actual date fields sent to backend -->
        <input type="hidden" name="start_date" id="startDateEdit"
            value="{{ $playerFee->start_date ? $playerFee->start_date->format('Y-m-d') : '' }}">
        <input type="hidden" name="end_date" id="endDateEdit"
            value="{{ $playerFee->end_date ? $playerFee->end_date->format('Y-m-d') : '' }}">

        <!-- Calculated Duration Info -->
        <div class="mb-3">
            <div
                class="bg-light-subtle border border-secondary-subtle rounded-3 p-2 d-flex justify-content-between align-items-center">
                <span class="text-secondary small fw-semibold text-uppercase">Calculated Duration</span>
                <span class="fw-bold text-dark small" id="calculatedDurationEdit">0 Month(s)</span>
            </div>
        </div>

        <!-- Warning Alert for Overlapping Fees -->
        <div id="paymentOverlapWarningEdit" class="alert alert-danger d-none py-2 px-3 mb-3 small fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <span id="paymentOverlapWarningTextEdit"></span>
        </div>

        <!-- Warning Alert for Joined Date -->
        <div id="joinedDateWarningEdit" class="alert alert-danger d-none py-2 px-3 mb-3 small fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <span id="joinedDateWarningTextEdit"></span>
        </div>

        <!-- Calculations Fields -->
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Subtotal</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="sub_totalamount" id="sub_totalamount_edit"
                        class="form-control border-start-0 ps-1 fw-semibold text-dark"
                        value="{{ $playerFee->sub_totalamount }}" readonly>
                </div>
                <p class="text-danger small mb-0" id="sub_totalamountError"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Penalty Amount</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="penalty_amount" id="penalty_amount_edit"
                        class="form-control border-start-0 ps-1 fw-semibold text-danger"
                        value="{{ $playerFee->penalty_amount ?? 0.00 }}" readonly>
                </div>
                <p class="text-danger small mb-0" id="penalty_amountError"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Discount Applied</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount_edit"
                        class="form-control border-start-0 ps-1 fw-semibold text-success"
                        value="{{ $playerFee->discount_amount }}">
                </div>
                <p class="text-danger small mb-0" id="discount_amountError"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">Total Amount</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="total_amt" id="total_amt_edit"
                        class="form-control border-start-0 ps-1 fw-bold text-primary"
                        value="{{ $playerFee->total_amt }}" readonly>
                </div>
                <p class="text-danger small mb-0" id="total_amtError"></p>
            </div>
        </div>

        <!-- Payment Type -->
        <div class="mb-3">
            <label for="payment_type_edit" class="form-label fw-semibold text-dark small">Payment Method</label>
            <select name="payment_type" id="payment_type_edit" class="form-select" required>
                <option value="cash" {{ $playerFee->payment_type === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="card" {{ $playerFee->payment_type === 'card' ? 'selected' : '' }}>Card</option>
                <option value="upi" {{ $playerFee->payment_type === 'upi' ? 'selected' : '' }}>UPI</option>
            </select>
            <p class="text-danger small mb-0" id="payment_typeError"></p>
        </div>

        <!-- UPI Details -->
        <div id="upiFieldsEdit"
            class="{{ $playerFee->payment_type === 'upi' ? '' : 'd-none' }} bg-light p-3 rounded-3 border mb-3">
            <div class="mb-3">
                <label for="upi_id_edit" class="form-label fw-semibold text-dark small">UPI ID / Reference
                    Number</label>
                <input type="text" name="upi_id" id="upi_id_edit" class="form-control"
                    placeholder="e.g. name@upi or txn_12345" value="{{ $playerFee->upi_id }}">
                <p class="text-danger small mb-0" id="upi_idError"></p>
            </div>
            <div class="mb-3">
                <label for="img_upi_edit" class="form-label fw-semibold text-dark small">Upload New Transaction Slip /
                    Screenshot</label>
                <input type="file" name="img_upi" id="img_upi_edit" class="form-control" accept="image/*" @if ($playerFee->img_upi) data-default-file="{{ asset($playerFee->img_upi) }}" @endif>
                <p class="text-danger small mb-0" id="img_upiError"></p>
            </div>
            @if ($playerFee->img_upi)
                <div class="mt-2">
                    <span class="small text-secondary d-block mb-1">Current Receipt Slip:</span>
                    <a href="{{ asset($playerFee->img_upi) }}" target="_blank" class="d-inline-block">
                        <img src="{{ asset($playerFee->img_upi) }}" class="img-thumbnail rounded-3"
                            style="max-height: 120px;">
                    </a>
                </div>
            @endif
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status_edit" class="form-label fw-semibold text-dark small">Status</label>
            <select name="status" id="status_edit" class="form-select" required>
                <option value="pending" {{ $playerFee->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $playerFee->status === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
            <p class="text-danger small mb-0" id="statusError"></p>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">
            <i class="bi bi-check-circle me-1"></i> Update Fee Payment
        </button>
    </form>
</div>

<script>
    $(document).ready(function() {

        // State variables
        let monthlyFeeSum = parseFloat($('#batch_fee').val()) || 0;
        let discountSettings = null;
        let playerId = $('#selected_player_id').val();

        // Load details initially
        if (playerId) {
            $.ajax({
                url: '{{ url('player-fees/player-details') }}/' + playerId,
                method: 'GET',
                success: function(response) {
                    discountSettings = response;
                    calculateFeesEdit();
                },
                error: function() {
                    toastr.error('Failed to retrieve player batch details.');
                }
            });
        }

        // Function to calculate last day of a YYYY-MM month
        function getLastDayOfMonthEdit(monthStr) {
            if (!monthStr) return '';
            let parts = monthStr.split('-');
            let year = parseInt(parts[0]);
            let month = parseInt(parts[1]);
            let lastDay = new Date(year, month, 0).getDate();
            return year + '-' + String(month).padStart(2, '0') + '-' + String(lastDay).padStart(2, '0');
        }

        // Initialize Flatpickr MonthSelect pickers
        flatpickr("#startMonthEdit", {
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y"
                })
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr) {
                    $('#startDateEdit').val(dateStr + '-01');
                } else {
                    $('#startDateEdit').val('');
                }
                calculateFeesEdit();
            }
        });

        flatpickr("#endMonthEdit", {
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y"
                })
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr) {
                    let lastDate = getLastDayOfMonthEdit(dateStr);
                    $('#endDateEdit').val(lastDate);
                } else {
                    $('#endDateEdit').val('');
                }
                calculateFeesEdit();
            }
        });

        // Manual amount editing recalculation
        $(document).on('input', '#sub_totalamount_edit, #discount_amount_edit, #penalty_amount_edit', function() {
            let subtotal = parseFloat($('#sub_totalamount_edit').val()) || 0;
            let discountAmt = parseFloat($('#discount_amount_edit').val()) || 0;
            let penaltyAmt = parseFloat($('#penalty_amount_edit').val()) || 0;
            let totalAmt = subtotal + penaltyAmt - discountAmt;
            if (totalAmt < 0) totalAmt = 0;
            $('#total_amt_edit').val(totalAmt.toFixed(2));
        });

        // Calculate Fees
        function calculateFeesEdit() {
            let startVal = $('#startDateEdit').val();
            let endVal = $('#endDateEdit').val();

            if (!startVal || !endVal || monthlyFeeSum === 0) {
                $('#paymentOverlapWarningEdit').addClass('d-none');
                $('#paymentOverlapWarningTextEdit').text('');
                $('#editPlayerFeeForm button[type="submit"]').prop('disabled', false);
                return; // Keep initial database values if something is missing
            }

            let start = new Date(startVal);
            let end = new Date(endVal);

            if (end < start) {
                $('#end_dateError').text('End date cannot be earlier than start date.');
                $('#paymentOverlapWarningEdit').addClass('d-none');
                $('#paymentOverlapWarningTextEdit').text('');
                $('#editPlayerFeeForm button[type="submit"]').prop('disabled', false);
                return;
            } else {
                $('#end_dateError').text('');
            }

            // Check if period starts before player joined the batch
            let joinedAtStr = null;
            let currentBatchId = $('#editPlayerFeeForm input[name="batch_id"]').val();
            if (discountSettings && discountSettings.batches) {
                let currentBatch = discountSettings.batches.find(b => b.id == currentBatchId);
                if (currentBatch) {
                    joinedAtStr = currentBatch.joined_at;
                }
            }
            if (joinedAtStr && startVal) {
                let joinedDate = new Date(joinedAtStr);
                let joinedMonthStart = new Date(joinedDate.getFullYear(), joinedDate.getMonth(), 1);
                let startDate = new Date(startVal);
                if (startDate < joinedMonthStart) {
                    let formattedJoinedDate = joinedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    $('#joinedDateWarningTextEdit').text(`Player joined this batch on ${formattedJoinedDate}. Selected fee period starts before the joining date.`);
                    $('#joinedDateWarningEdit').removeClass('d-none');
                } else {
                    $('#joinedDateWarningEdit').addClass('d-none');
                    $('#joinedDateWarningTextEdit').text('');
                }
            } else {
                $('#joinedDateWarningEdit').addClass('d-none');
                $('#joinedDateWarningTextEdit').text('');
            }

            // Calculate duration in days
            let timeDiff = end.getTime() - start.getTime();
            let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

            // Convert to months
            let durationMonths = Math.round(diffDays / 30.44);
            if (durationMonths < 1) durationMonths = 1;

            $('#calculatedDurationEdit').text(durationMonths + ' Month(s) (' + diffDays + ' Days)');

            let subtotal = monthlyFeeSum * durationMonths;
            let discountValue = 0;
            let totalPenalty = 0;
            let isAnyMonthLate = false;

            // --- 1. LATE FEE / PENALTY CALCULATION ---
            if (discountSettings && discountSettings.penalty_allow) {
                const penaltyDays = parseInt(discountSettings.penalty_days) || 0;
                const penaltyType = discountSettings.penalty_type || 'fixed';
                const penaltyAmtSetting = parseFloat(discountSettings.penalty_amount) || 0;

                const today = new Date();
                const currentYear = today.getFullYear();
                const currentMonth = today.getMonth() + 1; // 1-indexed (Jan=1, Feb=2...)
                const currentDay = today.getDate();

                // Set up a loop starting from the Start Month to the End Month
                let currentCursor = new Date(start.getFullYear(), start.getMonth(), 1);
                let endLimit = new Date(end.getFullYear(), end.getMonth(), 1);

                while (currentCursor <= endLimit) {
                    let targetYear = currentCursor.getFullYear();
                    let targetMonth = currentCursor.getMonth() + 1;

                    let isLate = (targetYear < currentYear) || 
                                 (targetYear === currentYear && targetMonth < currentMonth) || 
                                 (targetYear === currentYear && targetMonth === currentMonth && currentDay > penaltyDays);

                    if (isLate) {
                        isAnyMonthLate = true;
                        let monthPenalty = (penaltyType === 'fixed') 
                            ? penaltyAmtSetting 
                            : monthlyFeeSum * (penaltyAmtSetting / 100);
                        totalPenalty += monthPenalty;
                    }

                    currentCursor.setMonth(currentCursor.getMonth() + 1);
                }
            }

            // --- 2. PREPAYMENT DISCOUNT CALCULATION ---
            let discountAmt = 0;
            if (discountSettings && !isAnyMonthLate) {
                let settings = discountSettings;
                
                if (durationMonths >= 12) {
                    discountValue = settings.discount_yearly;
                } else if (durationMonths >= 6) {
                    discountValue = settings.discount_half_yearly;
                } else if (durationMonths >= 3) {
                    discountValue = settings.discount_quarterly;
                } else if (durationMonths >= 1) {
                    discountValue = settings.discount_monthly;
                }

                discountAmt = (settings.discount_type === 'percentage') 
                    ? subtotal * (discountValue / 100) 
                    : discountValue;

                if (discountAmt > subtotal) {
                    discountAmt = subtotal;
                }
            }

            // --- 3. TOTAL AMOUNT & RENDERING ---
            let totalAmt = subtotal + totalPenalty - discountAmt;

            $('#sub_totalamount_edit').val(subtotal.toFixed(2));
            $('#penalty_amount_edit').val(totalPenalty.toFixed(2));
            $('#discount_amount_edit').val(discountAmt.toFixed(2));
            $('#total_amt_edit').val(totalAmt.toFixed(2));

            if (totalPenalty > 0) {
                $('#penalty_amount_edit').prop('readonly', false);
            } else {
                $('#penalty_amount_edit').prop('readonly', true);
            }

            // Check for payment overlap
            let excludeId = '{{ $playerFee->id }}';
            let batchId = $('#editPlayerFeeForm input[name="batch_id"]').val();
            if (playerId && batchId && startVal && endVal) {
                $.ajax({
                    url: '/player-fees/check-overlap',
                    method: 'GET',
                    data: {
                        player_id: playerId,
                        batch_id: batchId,
                        start_date: startVal,
                        end_date: endVal,
                        exclude_id: excludeId
                    },
                    success: function (response) {
                        if (response.overlap) {
                            $('#paymentOverlapWarningTextEdit').text(response.message);
                            $('#paymentOverlapWarningEdit').removeClass('d-none');
                            $('#editPlayerFeeForm button[type="submit"]').prop('disabled', true);
                        } else {
                            $('#paymentOverlapWarningEdit').addClass('d-none');
                            $('#paymentOverlapWarningTextEdit').text('');
                            
                            let hasJoinedDateWarning = !$('#joinedDateWarningEdit').hasClass('d-none');
                            if (hasJoinedDateWarning) {
                                $('#editPlayerFeeForm button[type="submit"]').prop('disabled', true);
                            } else {
                                $('#editPlayerFeeForm button[type="submit"]').prop('disabled', false);
                            }
                        }
                    },
                    error: function () {
                        $('#paymentOverlapWarningEdit').addClass('d-none');
                        $('#paymentOverlapWarningTextEdit').text('');
                        
                        let hasJoinedDateWarning = !$('#joinedDateWarningEdit').hasClass('d-none');
                        if (hasJoinedDateWarning) {
                            $('#editPlayerFeeForm button[type="submit"]').prop('disabled', true);
                        } else {
                            $('#editPlayerFeeForm button[type="submit"]').prop('disabled', false);
                        }
                    }
                });
            } else {
                $('#paymentOverlapWarningEdit').addClass('d-none');
                $('#paymentOverlapWarningTextEdit').text('');
                
                let hasJoinedDateWarning = !$('#joinedDateWarningEdit').hasClass('d-none');
                if (hasJoinedDateWarning) {
                    $('#editPlayerFeeForm button[type="submit"]').prop('disabled', true);
                } else {
                    $('#editPlayerFeeForm button[type="submit"]').prop('disabled', false);
                }
            }
        }

        // Payment Type changed
        $('#payment_type_edit').on('change', function() {
            let val = $(this).val();
            if (val === 'upi') {
                $('#upiFieldsEdit').removeClass('d-none');
                $('#upi_id_edit').prop('required', true);
            } else {
                $('#upiFieldsEdit').addClass('d-none');
                $('#upi_id_edit').prop('required', false);
            }
        });

        // Form Submit
        $('#editPlayerFeeForm').on('submit', function(e) {
            e.preventDefault();

            let hasOverlapWarning = !$('#paymentOverlapWarningEdit').hasClass('d-none');
            let hasJoinedDateWarning = !$('#joinedDateWarningEdit').hasClass('d-none');
            if (hasOverlapWarning || hasJoinedDateWarning) {
                toastr.error('Please resolve the warnings before submitting.');
                return false;
            }

            submitFormAjax(this);
        });
    });
</script>
