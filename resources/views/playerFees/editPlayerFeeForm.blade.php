<div class="container py-3">
    <form id="editPlayerFeeForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" id="url" value="{{ route('player-fees.update', $playerFee->id) }}">
        <input type="hidden" id="selected_player_id" value="{{ $playerFee->player_id }}">

        <!-- Player (Read Only) -->
        <div class="mb-3">
            <label class="form-label fw-semibold text-secondary small">Player</label>
            <input type="text" class="form-control bg-light" value="{{ $playerFee->player->firstname }} {{ $playerFee->player->lastname }} ({{ $playerFee->player->email }})" readonly>
            <input type="hidden" name="player_id" value="{{ $playerFee->player_id }}">
        </div>

        <!-- Enrolled Batches Section (Dynamic) -->
        <div id="playerBatchesSectionEdit" class="mb-3"></div>

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
        <input type="hidden" name="start_date" id="startDateEdit" value="{{ $playerFee->start_date ? $playerFee->start_date->format('Y-m-d') : '' }}">
        <input type="hidden" name="end_date" id="endDateEdit" value="{{ $playerFee->end_date ? $playerFee->end_date->format('Y-m-d') : '' }}">

        <!-- Calculated Duration Info -->
        <div class="mb-3">
            <div class="bg-light-subtle border border-secondary-subtle rounded-3 p-2 d-flex justify-content-between align-items-center">
                <span class="text-secondary small fw-semibold text-uppercase">Calculated Duration</span>
                <span class="fw-bold text-dark small" id="calculatedDurationEdit">0 Month(s)</span>
            </div>
        </div>

        <!-- Calculations Fields -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Subtotal</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="sub_totalamount" id="sub_totalamount_edit" class="form-control border-start-0 ps-1 fw-semibold text-dark" value="{{ $playerFee->sub_totalamount }}">
                </div>
                <p class="text-danger small mb-0" id="sub_totalamountError"></p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Discount Applied</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount_edit" class="form-control border-start-0 ps-1 fw-semibold text-success" value="{{ $playerFee->discount_amount }}">
                </div>
                <p class="text-danger small mb-0" id="discount_amountError"></p>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Total Amount</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-secondary border-end-0">₹</span>
                    <input type="number" step="0.01" name="total_amt" id="total_amt_edit" class="form-control border-start-0 ps-1 fw-bold text-primary" value="{{ $playerFee->total_amt }}">
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
        <div id="upiFieldsEdit" class="{{ $playerFee->payment_type === 'upi' ? '' : 'd-none' }} bg-light p-3 rounded-3 border mb-3">
            <div class="mb-3">
                <label for="upi_id_edit" class="form-label fw-semibold text-dark small">UPI ID / Reference Number</label>
                <input type="text" name="upi_id" id="upi_id_edit" class="form-control" placeholder="e.g. name@upi or txn_12345" value="{{ $playerFee->upi_id }}">
                <p class="text-danger small mb-0" id="upi_idError"></p>
            </div>
            <div class="mb-3">
                <label for="img_upi_edit" class="form-label fw-semibold text-dark small">Upload New Transaction Slip / Screenshot</label>
                <input type="file" name="img_upi" id="img_upi_edit" class="form-control" accept="image/*">
                <p class="text-danger small mb-0" id="img_upiError"></p>
            </div>
            @if ($playerFee->img_upi)
                <div class="mt-2">
                    <span class="small text-secondary d-block mb-1">Current Receipt Slip:</span>
                    <a href="{{ asset($playerFee->img_upi) }}" target="_blank" class="d-inline-block">
                        <img src="{{ asset($playerFee->img_upi) }}" class="img-thumbnail rounded-3" style="max-height: 120px;">
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
    $(document).ready(function () {


        // State variables
        let monthlyFeeSum = 0;
        let discountSettings = null;
        let playerId = $('#selected_player_id').val();

        // Load details initially
        if (playerId) {
            $.ajax({
                url: '{{ url("player-fees/player-details") }}/' + playerId,
                method: 'GET',
                success: function (response) {
                    discountSettings = response;
                    monthlyFeeSum = 0;

                    let html = '<div class="card border border-light-subtle rounded-3 p-3 bg-light mb-3">';
                    html += '<h6 class="fw-bold mb-2 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Enrolled Batches</h6>';

                    if (response.batches.length === 0) {
                        html += '<p class="text-warning small mb-0"><i class="bi bi-exclamation-triangle me-1"></i> Player has no active batch assignments.</p>';
                    } else {
                        response.batches.forEach(function (batch) {
                            html += '<div class="d-flex justify-content-between align-items-center mb-1">';
                            html += '  <div class="form-check mb-0 d-flex align-items-center">';
                            html += '    <input class="form-check-input batch-fee-checkbox-edit me-2" type="checkbox" value="' + batch.fees + '" checked id="batch_chk_edit_' + batch.id + '">';
                            html += '    <label class="form-check-label small text-secondary" for="batch_chk_edit_' + batch.id + '">';
                            html +=        batch.name + ' (' + batch.sport + ' - ' + batch.level + ')';
                            html += '    </label>';
                            html += '  </div>';
                            html += '  <span class="fw-bold small text-dark">₹ ' + batch.fees.toFixed(2) + '</span>';
                            html += '</div>';
                            monthlyFeeSum += batch.fees;
                        });
                        html += '<hr class="my-2">';
                        html += '<div class="d-flex justify-content-between align-items-center">';
                        html += '  <span class="fw-bold text-dark small">Monthly Total</span>';
                        html += '  <span class="fw-bold text-primary" id="monthlyTotalDisplayEdit">₹ ' + monthlyFeeSum.toFixed(2) + '</span>';
                        html += '</div>';
                    }
                    html += '</div>';

                    $('#playerBatchesSectionEdit').html(html);
                    calculateFeesEdit();
                },
                error: function () {
                    toastr.error('Failed to retrieve player batch details.');
                }
            });
        }

        // Batch Checkboxes changes handler
        $('#playerBatchesSectionEdit').on('change', '.batch-fee-checkbox-edit', function () {
            let sum = 0;
            $('.batch-fee-checkbox-edit:checked').each(function () {
                sum += parseFloat($(this).val()) || 0;
            });
            monthlyFeeSum = sum;
            $('#monthlyTotalDisplayEdit').text('₹ ' + monthlyFeeSum.toFixed(2));
            calculateFeesEdit();
        });

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
        $(document).on('input', '#sub_totalamount_edit, #discount_amount_edit', function () {
            let subtotal = parseFloat($('#sub_totalamount_edit').val()) || 0;
            let discountAmt = parseFloat($('#discount_amount_edit').val()) || 0;
            let totalAmt = subtotal - discountAmt;
            if (totalAmt < 0) totalAmt = 0;
            $('#total_amt_edit').val(totalAmt.toFixed(2));
        });

        // Calculate Fees
        function calculateFeesEdit() {
            let startVal = $('#startDateEdit').val();
            let endVal = $('#endDateEdit').val();

            if (!startVal || !endVal || monthlyFeeSum === 0) {
                return; // Keep initial database values if something is missing
            }

            let start = new Date(startVal);
            let end = new Date(endVal);

            if (end < start) {
                $('#end_dateError').text('End date cannot be earlier than start date.');
                return;
            } else {
                $('#end_dateError').text('');
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
                var discountAmt = parseFloat($('#discount_amount_edit').val()) || 0;
            }

            let totalAmt = subtotal - discountAmt;

            // Render
            $('#sub_totalamount_edit').val(subtotal.toFixed(2));
            $('#discount_amount_edit').val(discountAmt.toFixed(2));
            $('#total_amt_edit').val(totalAmt.toFixed(2));
        }

        // Payment Type changed
        $('#payment_type_edit').on('change', function () {
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
        $('#editPlayerFeeForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

            $.ajax({
                url: $('#url').val(),
                method: 'POST', // Sent as POST with _method = PUT in the form
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    toastr.success(response.message);
                    $('#offcanvasScrolling').offcanvas('hide');
                    $('#datatable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Update Fee Payment');
                    $('.text-danger').text('');
                    
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
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
