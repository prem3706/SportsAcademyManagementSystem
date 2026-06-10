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
                        ({{ $player->phone }})</option>
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
                <input type="text" id="sub_totalamount_display" class="form-control bg-light fw-semibold text-dark"
                    readonly value="₹ 0.00">
                <input type="hidden" name="sub_totalamount" id="sub_totalamount_val" value="0.00">
                <p class="text-danger small mb-0" id="sub_totalamountError"></p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Discount Applied</label>
                <input type="text" id="discount_amount_display"
                    class="form-control bg-light fw-semibold text-success" readonly value="₹ 0.00">
                <input type="hidden" name="discount_amount" id="discount_amount_val" value="0.00">
                <p class="text-danger small mb-0" id="discount_amountError"></p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Total Amount</label>
                <input type="text" id="total_amt_display" class="form-control bg-light fw-bold text-primary" readonly
                    value="₹ 0.00">
                <input type="hidden" name="total_amt" id="total_amt_val" value="0.00">
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
                <option value="pending" selected>Pending</option>
                <option value="paid">Paid</option>
            </select>
            <p class="text-danger small mb-0" id="statusError"></p>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold rounded-3">
            <i class="bi bi-check-circle me-1"></i> Record Fee Payment
        </button>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2 inside the Offcanvas
        if ($('#player_id').length) {
            $('#player_id').select2({
                dropdownParent: $('#offcanvasScrolling'),
                width: '100%'
            });
        }

        // Initialize Flatpickr for dates if available
        if (typeof flatpickr !== 'undefined') {
            flatpickr('#startDate, #endDate', {
                dateFormat: 'Y-m-d',
                allowInput: true
            });
        }

        // State variables
        let monthlyFeeSum = 0;
        let discountSettings = null;

        // Player Select Handler
        $('#player_id').on('change', function() {
            let playerId = $(this).val();
            if (!playerId) {
                $('#playerBatchesSection').addClass('d-none').html('');
                monthlyFeeSum = 0;
                discountSettings = null;
                calculateFees();
                return;
            }

            $.ajax({
                url: '{{ url('player-fees/player-details') }}/' + playerId,
                method: 'GET',
                success: function(response) {
                    discountSettings = response;
                    monthlyFeeSum = 0;


                    let html =
                        '<div class="card border border-light-subtle rounded-3 p-3 bg-light mb-3">';
                    html +=
                        '<h6 class="fw-bold mb-2 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Enrolled Batches</h6>';

                    if (response.batches.length === 0) {
                        html +=
                            '<p class="text-warning small mb-0"><i class="bi bi-exclamation-triangle me-1"></i> Player has no active batch assignments.</p>';
                    } else {
                        response.batches.forEach(function(batch) {
                            html +=
                                '<div class="d-flex justify-content-between align-items-center mb-1">';
                            html += '  <span class="small text-secondary">' + batch
                                .name + ' (' + batch.sport + ' - ' + batch.level +
                                ')</span>';
                            html += '  <span class="fw-bold small text-dark">₹ ' +
                                batch.fees.toFixed(2) + '</span>';
                            html += '</div>';
                            monthlyFeeSum += batch.fees;
                        });
                        html += '<hr class="my-2">';
                        html +=
                            '<div class="d-flex justify-content-between align-items-center">';
                        html +=
                            '  <span class="fw-bold text-dark small">Monthly Total</span>';
                        html += '  <span class="fw-bold text-primary">₹ ' + monthlyFeeSum
                            .toFixed(2) + '</span>';
                        html += '</div>';
                    }
                    html += '</div>';

                    $('#playerBatchesSection').html(html).removeClass('d-none');
                    calculateFees();
                },
                error: function() {
                    toastr.error('Failed to retrieve player batch details.');
                }
            });
        });

        // Date changes handler
        $('#startDate, #endDate').on('change', function() {
            calculateFees();
        });

        // Calculate Fees
        function calculateFees() {
            let startVal = $('#startDate').val();
            let endVal = $('#endDate').val();

            if (!startVal || !endVal || monthlyFeeSum === 0) {
                resetCalculation();
                return;
            }

            let start = new Date(startVal);
            let end = new Date(endVal);

            if (end < start) {
                $('#end_dateError').text('End date cannot be earlier than start date.');
                resetCalculation();
                return;
            } else {
                $('#end_dateError').text('');
            }

            // Calculate duration in days
            let timeDiff = end.getTime() - start.getTime();
            let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Include the starting day

            // Convert to months (approx 30.44 days per month)
            let durationMonths = Math.round(diffDays / 30.44);
            if (durationMonths < 1) durationMonths = 1;

            $('#calculatedDuration').text(durationMonths + ' Month(s) (' + diffDays + ' Days)');

            let subtotal = monthlyFeeSum * durationMonths;
            let discountValue = 0;

            if (discountSettings) {
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

                var discountAmt = 0;
                if (settings.discount_type === 'percentage') {
                    discountAmt = subtotal * (discountValue / 100);
                } else {
                    discountAmt = discountValue;
                }

                if (discountAmt > subtotal) {
                    discountAmt = subtotal;
                }
            } else {
                var discountAmt = 0;
            }

            let totalAmt = subtotal - discountAmt;

            // Render
            $('#sub_totalamount_display').val('₹ ' + subtotal.toFixed(2));
            $('#discount_amount_display').val('₹ ' + discountAmt.toFixed(2));
            $('#total_amt_display').val('₹ ' + totalAmt.toFixed(2));

            // Hidden values
            $('#sub_totalamount_val').val(subtotal.toFixed(2));
            $('#discount_amount_val').val(discountAmt.toFixed(2));
            $('#total_amt_val').val(totalAmt.toFixed(2));
        }

        function resetCalculation() {
            $('#calculatedDuration').text('0 Month(s)');
            $('#sub_totalamount_display').val('₹ 0.00');
            $('#discount_amount_display').val('₹ 0.00');
            $('#total_amt_display').val('₹ 0.00');

            $('#sub_totalamount_val').val('0.00');
            $('#discount_amount_val').val('0.00');
            $('#total_amt_val').val('0.00');
        }

        // Payment Type changed
        $('#payment_type').on('change', function() {
            let val = $(this).val();
            if (val === 'upi') {
                $('#upiFields').removeClass('d-none');
                $('#upi_id').prop('required', true);
                $('#img_upi').prop('required', true);
            } else {
                $('#upiFields').addClass('d-none');
                $('#upi_id').prop('required', false).val('');
                $('#img_upi').prop('required', false).val('');
            }
        });

        // Form Submit
        $('#addPlayerFeeForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            // Disable submit button to prevent double click
            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Recording...');

            $.ajax({
                url: $('#url').val(),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    $('#offcanvasScrolling').offcanvas('hide');
                    $('#datatable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(
                        '<i class="bi bi-check-circle me-1"></i> Record Fee Payment');
                    $('.text-danger').text('');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key + 'Error').text(value[0]);
                        });
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
                    }
                }
            });
        });
    });
</script>
