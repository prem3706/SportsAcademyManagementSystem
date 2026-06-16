// Open form inside Bootstrap Offcanvas using AJAX
function openOffcanvasForm(url, title, onSuccess) {
    $('#offcanvasScrollingLabel').text(title);
    $('#offCanvasContent').html(`
        <div class="modern-loader-container">
            <div class="modern-loader"></div>
            <div class="modern-loader-text">Loading form...</div>
        </div>
    `);

    $.ajax({
        type: 'GET',
        url: url,
        success: function (response) {
            $('#offCanvasContent').html(response);
            if (onSuccess) onSuccess(response);
        },
        error: function () {
            toastr.error('Failed to load form. Please try again.');
            $('#offCanvasContent').html('<div class="alert alert-danger m-3">Failed to load form.</div>');
        }
    });
}

// Close the Bootstrap Offcanvas
function closeOffcanvasForm() {
    $('#offcanvasScrolling').offcanvas('hide');
}

// Submit form using AJAX (supports file upload & displays validation errors)
function submitFormAjax(formSelector, onSuccess, onError) {
    let $form = $(formSelector);
    let formEl = $form[0];
    if (!formEl) return;

    let formData = new FormData(formEl);
    let url = $form.find('#url').val() || $form.find('input[id$="_url"]').val() || $form.attr('action');
    let method = 'POST';

    let submitBtn = $form.find('button[type="submit"], .btn-submit');
    let originalHtml = submitBtn.html();

    submitBtn.prop('disabled', true);
    let spinner = submitBtn.find('.spinner-border');
    if (spinner.length) {
        spinner.removeClass('d-none');
    } else {
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>' + originalHtml);
    }

    $form.find('.text-danger').text('');

    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message);

            if ($form.closest('#offcanvasScrolling').length) {
                closeOffcanvasForm();
            }

            if ($('#datatable').length) {
                $('#datatable').DataTable().ajax.reload();
            }

            if (onSuccess) {
                onSuccess(response);
            } else {
                formEl.reset();
            }
        },
        error: function (xhr) {
            submitBtn.prop('disabled', false).html(originalHtml);

            if (xhr.status === 422) {
                let response = xhr.responseJSON;
                if (response) {
                    if (response.message) {
                        toastr.error(response.message);
                    }
                    if (response.errors) {
                        $.each(response.errors, function (key, value) {
                            let errorId = key.replace(/\./g, '_') + 'Error';
                            let errorEl = $form.find('#' + errorId).length ? $form.find('#' + errorId) : $form.find('#' + key + 'Error');
                            if (errorEl.length) {
                                errorEl.text(value[0]);
                            } else {
                                $('#' + errorId).text(value[0]);
                                $('#' + key + 'Error').text(value[0]);
                            }
                        });
                    }
                }
            } else {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
            }

            if (onError) onError(xhr);
        }
    });
}

// Delete a resource using SweetAlert confirmation and AJAX DELETE request
function deleteResourceAjax(url, warningText, onSuccess) {
    if (!warningText) {
        warningText = 'This record will be deleted permanently!';
    }

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-danger mx-2",
            cancelButton: "btn btn-secondary"
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: warningText,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "Cancel",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "DELETE",
                success: function (response) {
                    toastr.success(response.message);

                    if ($('#datatable').length) {
                        $('#datatable').DataTable().ajax.reload();
                    }

                    if (onSuccess) onSuccess(response);
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
                }
            });
        }
    });
}


$(document).ready(function () {

    /* ---  CONFIGURATIONS & INITIALIZERS --- */

    // SweetAlert Mixin Configuration
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-danger mx-2",
            cancelButton: "btn btn-secondary"
        },
        buttonsStyling: false
    });

    // Toastr Notifications Configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 3000
    };

    // Time Picker Initialization (Flatpickr)
    function initTimePicker() {
        flatpickr("#startTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false
        });

        flatpickr("#endTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false
        });
    }

    // Date Picker Initialization (Flatpickr MonthSelect)
    function initDatePicker() {
        if ($('#startMonth').length) {
            flatpickr("#startMonth", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "Y-m",
                        altFormat: "F Y"
                    })
                ],
                onChange: function (selectedDates, dateStr, instance) {
                    if (dateStr) {
                        $('#startDate').val(dateStr + '-01');
                    } else {
                        $('#startDate').val('');
                    }
                    calculateFees();
                }
            });
        }

        if ($('#endMonth').length) {
            flatpickr("#endMonth", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "Y-m",
                        altFormat: "F Y"
                    })
                ],
                onChange: function (selectedDates, dateStr, instance) {
                    if (dateStr) {
                        let lastDate = getLastDayOfMonth(dateStr);
                        $('#endDate').val(lastDate);
                    } else {
                        $('#endDate').val('');
                    }
                    calculateFees();
                }
            });
        }
    }

    // Standard Flatpickr Datepicker Initializer
    function initFlatpickrDate(container) {
        let target = container ? $(container).find('.datepicker-input') : $('.datepicker-input');
        target.each(function () {
            if (!this._flatpickr) {
                flatpickr(this, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d M Y",
                    allowInput: true
                });
            }
        });
    }

    // Select2 Custom Styling Initializer
    function initSelect2(container) {
        let target = container ? $(container).find('select.select2, select.form-select') : $('select.select2, select.form-select');
        target = target.not('.dataTables_length select, .no-select2');

        target.each(function () {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                let select = $(this);
                let dropdownParent = select.closest('#offcanvasScrolling').length ? $('#offcanvasScrolling') : null;
                let isSearchable = select.hasClass('select2') && !select.hasClass('select2-no-search');

                let options = {
                    width: '100%',
                    dropdownParent: dropdownParent
                };

                if (!isSearchable) {
                    options.minimumResultsForSearch = Infinity;
                }

                select.select2(options);
            }
        });
    }

    // Run initial Select2 styling
    initSelect2();
    initFlatpickrDate();

    // Auto-initialize Select2 on dynamically loaded content (AJAX Complete)
    $(document).ajaxComplete(function () {
        initSelect2('#offCanvasContent');
        initFlatpickrDate('#offCanvasContent');
    });

    /* --- 3. CSRF TOKEN SETUP --- */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* --- 4. DATATABLE CUSTOM FILTERS & REFRESH LOGIC --- */

    // Reload Table on filter values change
    let isResetting = false;
    $(document).on('change', '#statusFilter, #roleFilter, #sportFilter, #levelFilter, #batchFilter, #monthFilter, #yearFilter, #paymentTypeFilter, #playerFilter', function () {
        if (isResetting) return;
        $('#datatable').DataTable().ajax.reload();
        checkRefreshButton();
    });

    // Refresh Table Filters Button click handler
    $(document).on('click', '#refreshTableBtn', function () {
        var currentMonth = new Date().getMonth() + 1;
        var currentYear = new Date().getFullYear();
        isResetting = true;

        // Reset all Select2 values
        $('#statusFilter').val('').trigger('change');
        $('#roleFilter').val('').trigger('change');
        $('#sportFilter').val('').trigger('change');
        $('#levelFilter').val('').trigger('change');
        $('#batchFilter').val('').trigger('change');
        $('#monthFilter').val(currentMonth).trigger('change');
        $('#yearFilter').val(currentYear).trigger('change');
        $('#paymentTypeFilter').val('').trigger('change');

        if ($('#playerFilter').length) {
            $('#playerFilter').val(null).trigger('change');
        }

        isResetting = false;
        $('#datatable').DataTable().ajax.reload();
        $(this).addClass('d-none');
    });

    // Toggle visibility of Refresh Button based on filters state
    function checkRefreshButton() {
        let status = $('#statusFilter').val() || '';
        let role = $('#roleFilter').val() || '';
        let sport = $('#sportFilter').val() || '';
        let level = $('#levelFilter').val() || '';
        let batch = $('#batchFilter').val() || '';
        let month = $('#monthFilter').val() || '';
        let year = $('#yearFilter').val() || '';
        let payment_type = $('#paymentTypeFilter').val() || '';
        let player = $('#playerFilter').val() || '';

        if (status !== '' || role !== '' || sport !== '' || level !== '' || batch !== '' || month !== '' || year !== '' || payment_type !== '' || player !== '') {
            $('#refreshTableBtn').removeClass('d-none');
        } else {
            $('#refreshTableBtn').addClass('d-none');
        }
    }

    /* --- 5. BULK ACTIONS LOGIC --- */

    // Toggle Sticky Bulk Action Bar
    function toggleBulkButton() {
        let selectedCount = $('.user-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);

        if (selectedCount > 0) {
            $('#bulkActionBar').removeClass('d-none');
        } else {
            $('#bulkActionBar').addClass('d-none');
            $('#statusUpdate').val('');
        }
    }

    // Select/Deselect All Checkboxes
    $(document).on('change', '#select-all', function () {
        $('.user-checkbox').prop('checked', this.checked);
        toggleBulkButton();
    });

    // Single Checkbox selection handler
    $(document).on('change', '.user-checkbox', function () {
        let total = $('.user-checkbox').length;
        let checked = $('.user-checkbox:checked').length;
        $('#select-all').prop('checked', total === checked);
        toggleBulkButton();
    });

    // Reset Checkboxes on DataTable redraw/page change
    $('#users-table').on('draw.dt', function () {
        $('#select-all').prop('checked', false);
        toggleBulkButton();
    });

    // Bulk Delete operation
    function Bulkdelete(name, checkboxClass = '.user-checkbox') {
        $('#bulkDeleteBtn').off('click').on('click', function () {
            let ids = [];
            let url = $(this).data('url');

            $(`${checkboxClass}:checked`).each(function () {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                toastr.warning(`Please select at least one ${name}.`);
                return;
            }

            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: `Selected ${name} will be deleted permanently!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: { select: ids },
                        success: function (response) {
                            toastr.success(response.message);
                            $('#datatable').DataTable().ajax.reload();
                            $('#select-all').prop('checked', false);
                            $('#bulkActionBar').addClass('d-none');
                        },
                        error: function (xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
                        }
                    });
                }
            });
        });
    }



    // Bulk Status Update operation
    function BulkUpdateStatus(name, checkboxClass = '.user-checkbox') {
        $('#bulkUpdateBtn').off('click').on('click', function () {
            let ids = [];
            let status = $('#statusUpdate').val();
            let url = $(this).data('url');

            $(`${checkboxClass}:checked`).each(function () {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                toastr.warning(`Please select at least one ${name}.`);
                return;
            }

            if (status === '') {
                toastr.warning('Please select a status.');
                return;
            }

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _method: 'PATCH',
                    select: ids,
                    status: status
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#datatable').DataTable().ajax.reload();
                    $('#select-all').prop('checked', false);
                    $(`${checkboxClass}`).prop('checked', false);
                    $('#bulkActionBar').addClass('d-none');
                    $('#statusUpdate').val('');
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
                    }
                }
            });
        });
    }


    /* ---------------  USER MANAGEMENT ----------------------- */

    // Dynamically toggle Joining Date input on role selection
    $(document).on('change', '#role', function () {
        let selectedRole = $(this).val();
        if (selectedRole === 'coach') {
            $('#joining_date_div').hide();
        } else {
            $('#joining_date_div').show();
        }
    });

    // Password visibility toggle handler
    $(document).on('click', '#togglePassword', function () {
        const passwordField = $('#password');
        const icon = $('#toggleIcon');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        icon.toggleClass('bi-eye-slash bi-eye');
    });

    // Add User Form Open
    $(document).on('click', '#addUserBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Add User Form Submit
    $(document).on('submit', '#addUserForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Delete Single User
    $(document).on('click', '#deleteUserBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This user will be deleted permanently!');
    });

    Bulkdelete('users', '.user-checkbox');
    BulkUpdateStatus('users', '.user-checkbox');


    // Edit User Form Open
    $(document).on('click', '#editUserBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit User Form Submit
    $(document).on('submit', '#editUserForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    /* -------------------- SPORT MANAGEMENT -------------------*-- */

    // Add Sport Form Open
    $(document).on('click', '#addSportBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit Sport Form Open
    $(document).on('click', '#editSportBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Add Sport Form Submit
    $(document).on('submit', '#addSportForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Edit Sport Form Submit
    $(document).on('submit', '#editSportForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Delete Single Sport
    $(document).on('click', '#deleteSportBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This Sport will be deleted permanently!');
    });

    Bulkdelete('sports', '.user-checkbox');
    BulkUpdateStatus('sports', '.user-checkbox');

    /* ------------------- LEVEL MANAGEMENT -------------------- */

    // Add Level Form Open
    $(document).on('click', '#addLevelBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Add Level Form Submit
    $(document).on('submit', '#addLevelForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Delete Single Level
    $(document).on('click', '#deleteLevelBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This Level will be deleted permanently!');
    });

    // Edit Level Form Open
    $(document).on('click', '#editLevelBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit Level Form Submit
    $(document).on('submit', '#editLevelForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    Bulkdelete('levels', '.user-checkbox');
    BulkUpdateStatus('levels', '.user-checkbox');

    /* ------------------ SPORTSLEVEL MANAGEMENT -------------------- */

    // Add SportLevel Form Open
    $(document).on('click', '#addSportLevelBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Add Level Row dynamically to creation table
    $(document).on('click', '#addNewLevelBtn', function (e) {
        e.preventDefault();

        let levelId = $('#levelDropdown').val();
        let levelName = $('#levelDropdown option:selected').text();
        let fees = $('#levelFees').val();
        let index = $('#levelTableBody tr').length;

        if (levelId == '') {
            toastr.error('Please select level');
            return;
        }

        if (fees == '') {
            toastr.error('Please enter fees');
            return;
        }

        let exists = false;
        $('.level-id-input').each(function () {
            if ($(this).val() == levelId) {
                exists = true;
            }
        });

        if (exists) {
            toastr.error('This level already added');
            return;
        }

        let row = `
        <tr>
            <td>
                <div class="fw-semibold text-dark">${levelName}</div>
                <input type="hidden" class="level-id-input" name="levels[${index}][level_id]" value="${levelId}">
            </td>
            <td>
                <input type="number" name="levels[${index}][fees]" class="form-control" value="${fees}" placeholder="Enter fees">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeLevelBtn">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;

        $('#levelTableBody').append(row);
        $('#levelDropdown').val('');
        $('#levelFees').val('');
    });

    // Remove dynamic row from creation table
    $(document).on('click', '.removeLevelBtn', function () {
        $(this).closest('tr').remove();
    });

    // Add Sports Level Form Submit
    $(document).on('submit', '#addSportsLevelsForm', function (e) {
        e.preventDefault();
        submitFormAjax(this, function (response) {
            $('#levelTableBody').html('');
        });
    });

    // Load Edit Sports Level Form
    $(document).on('click', '#editSportsLevelsBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit Sports Level Form Submit
    $(document).on('submit', '#editSportsLevelsForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    /* ------------ BATCHES MANAGEMENT -------------- */

    // Sport dropdown change -> load levels dropdown
    $(document).on('change', '#sportDropdown', function () {
        let sportId = $(this).val();

        // Reset levels
        $('#levelDropdown').html('<option value="">Choose Level</option>').trigger('change');

        if (sportId != '') {
            $.ajax({
                url: '/get-sport-levels/' + sportId,
                method: 'GET',
                success: function (response) {
                    $.each(response, function (key, level) {
                        $('#levelDropdown').append(`<option value="${level.id}">${level.name}</option>`);
                    });
                    $('#levelDropdown').trigger('change');
                }
            });
        }
    });

    // Add Batch Form Open
    $(document).on('click', '#addBatchBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'), function () {
            initTimePicker();
        });
    });

    // Save Batch Button click -> trigger form submit
    $(document).on('click', '#saveBatchBtn', function (e) {
        e.preventDefault();
        $('#addBatchForm').trigger('submit');
    });

    // Add Batch Form Submit
    $(document).on('submit', '#addBatchForm', function (e) {
        e.preventDefault();

        // Capacity validation
        let capacity = parseInt($('input[name="capacity"]').val());
        let selectedPlayers = $('select[name="players[]"]').val();
        selectedPlayers = selectedPlayers ? selectedPlayers.length : 0;

        if (selectedPlayers > capacity) {
            toastr.error(`You can select maximum ${capacity} players only.`);
            return;
        }

        submitFormAjax(this, function (response) {
            $('#offcanvasScrolling select.select2').val(null).trigger('change');
        });
    });

    // Edit Batch Form Open
    $(document).on('click', '#editBatchBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'), function () {
            initTimePicker();
        });
    });

    // Update Batch Button click -> trigger form submit
    $(document).on('click', '#updateBatchBtn', function (e) {
        e.preventDefault();
        $('#editBatchForm').trigger('submit');
    });

    // Edit Batch Form Submit
    $(document).on('submit', '#editBatchForm', function (e) {
        e.preventDefault();

        // Capacity validation
        let capacity = parseInt($('input[name="capacity"]').val());
        let selectedPlayers = $('select[name="players[]"]').val();
        selectedPlayers = selectedPlayers ? selectedPlayers.length : 0;

        if (selectedPlayers > capacity) {
            toastr.error(`You can select maximum ${capacity} players only.`);
            return;
        }

        submitFormAjax(this);
    });

    // Delete Single Batch
    $(document).on('click', '#deleteBatchBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This Batch will be deleted permanently!');
    });

    Bulkdelete('batches', '.user-checkbox');
    BulkUpdateStatus('batches', '.user-checkbox');

    /* --- 11. PLAYERS MANAGEMENT --- */

    let assignmentIndex = 1;

    // Add dynamic Assignment Row inside Player offcanvas
    $(document).on('click', '#add_assignment_btn', function () {
        let container = $('#assignments_container');
        let index = assignmentIndex++;
        let sportsOptions = $('#sport_options_helper').html();

        let today = new Date().toISOString().split('T')[0];
        let rowHtml = `
        <div class="card border border-secondary-subtle rounded-4 mb-3 p-3 assignment-row bg-white animate__animated animate__fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold text-secondary small">Assignment #<span class="row-num">${index + 1}</span></span>
                <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-assignment-btn">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Sport</label>
                    <select class="form-select form-select-sm sport-select" name="assignments[${index}][sport_id]" required>
                        <option value="" disabled selected>Select sport</option>
                        ${sportsOptions}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Level</label>
                    <select class="form-select form-select-sm level-select" name="assignments[${index}][level_id]" required disabled>
                        <option value="" disabled selected>Select sport first</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Batch</label>
                    <select class="form-select form-select-sm batch-select" name="assignments[${index}][batch_id]" required disabled>
                        <option value="" disabled selected>Select level first</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Joined Date</label>
                    <input type="date" class="form-control form-control-sm joined-date-input" name="assignments[${index}][joined_at]" value="${today}" required>
                </div>
            </div>
        </div>`;

        container.append(rowHtml);
        initSelect2(container);
        initFlatpickrDate(container);
        updateRemoveButtons();
    });

    // Remove dynamic assignment row
    $(document).on('click', '.remove-assignment-btn', function () {
        $(this).closest('.assignment-row').remove();
        reindexRows();
        updateRemoveButtons();
    });

    // Reindex dynamic assignment row names and indexes
    function reindexRows() {
        assignmentIndex = 0;
        $('#assignments_container .assignment-row').each(function (idx) {
            let row = $(this);
            row.find('.row-num').text(idx + 1);
            row.find('.sport-select').attr('name', `assignments[${idx}][sport_id]`);
            row.find('.level-select').attr('name', `assignments[${idx}][level_id]`);
            row.find('.batch-select').attr('name', `assignments[${idx}][batch_id]`);
            row.find('.joined-date-input').attr('name', `assignments[${idx}][joined_at]`);
            assignmentIndex = idx + 1;
        });
    }

    // Toggle dynamic remove assignment row buttons visibility
    function updateRemoveButtons() {
        let rows = $('#assignments_container .assignment-row');
        if (rows.length <= 1) {
            rows.find('.remove-assignment-btn').addClass('d-none');
        } else {
            rows.find('.remove-assignment-btn').removeClass('d-none');
        }
    }

    // Cascade sport levels in player assignment row
    $(document).on('change', '.sport-select', function () {
        let select = $(this);
        let sportId = select.val();
        let row = select.closest('.assignment-row');
        let levelSelect = row.find('.level-select');
        let batchSelect = row.find('.batch-select');

        levelSelect.html('<option value="" disabled selected>Loading...</option>').prop('disabled', true).trigger('change');
        batchSelect.html('<option value="" disabled selected>Select level first</option>').prop('disabled', true).trigger('change');

        if (!sportId) return;

        $.ajax({
            type: 'GET',
            url: `get-sport-levels/${sportId}`,
            success: function (response) {
                levelSelect.html('<option value="" disabled selected>Select Level</option>').prop('disabled', false);
                response.forEach(level => {
                    let fees = level.pivot ? level.pivot.fees : 0;
                    levelSelect.append(`<option value="${level.id}" data-fees="${fees}">${level.name} (Fees: ₹${fees})</option>`);
                });
                levelSelect.trigger('change');
            },
            error: function () {
                levelSelect.html('<option value="" disabled selected>Error loading levels</option>').trigger('change');
            }
        });
    });

    // Cascade level batches in player assignment row
    $(document).on('change', '.level-select', function () {
        let select = $(this);
        let levelId = select.val();
        let row = select.closest('.assignment-row');
        let sportId = row.find('.sport-select').val();
        let batchSelect = row.find('.batch-select');

        batchSelect.html('<option value="" disabled selected>Loading...</option>').prop('disabled', true).trigger('change');

        if (!sportId || !levelId) return;

        $.ajax({
            type: 'GET',
            url: `get-batches/${sportId}/${levelId}`,
            success: function (response) {
                batchSelect.html('<option value="" disabled selected>Select Batch</option>').prop('disabled', false);
                if (response.length === 0) {
                    batchSelect.append('<option value="" disabled>No active batches found</option>');
                } else {
                    response.forEach(batch => {
                        batchSelect.append(`<option value="${batch.id}">${batch.name} (${batch.start_time} - ${batch.end_time})</option>`);
                    });
                }
                batchSelect.trigger('change');
            },
            error: function () {
                batchSelect.html('<option value="" disabled selected>Error loading batches</option>').trigger('change');
            }
        });
    });

    // Add Player Form Open
    $(document).on('click', '#addPlayerBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'), function () {
            assignmentIndex = 1;
        });
    });

    // Add Player Form Submit
    $(document).on('submit', '#addPlayerForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Edit Player Form Open
    $(document).on('click', '#editPlayerBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'), function () {
            assignmentIndex = $('#assignments_container .assignment-row').length;
        });
    });

    // Edit Player Form Submit
    $(document).on('submit', '#editPlayerForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Delete Single Player
    $(document).on('click', '#deletePlayerBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This player will be deleted permanently!');
    });

    Bulkdelete('players', '.user-checkbox');
    BulkUpdateStatus('players', '.user-checkbox');

    /* ----------------- PLAYER FEES MANAGEMENT ---------------- */

    // Form state variables
    let monthlyFeeSum = 0;
    let discountSettings = null;

    // Load active batches and pricing structure on Player select change
    $(document).on('change', '#player_id', function () {
        let playerId = $(this).val();
        if (!playerId) {
            $('#batchSelectContainer').addClass('d-none');
            $('#batch_id').html('<option value="">-- Choose Batch --</option>').trigger('change');
            monthlyFeeSum = 0;
            discountSettings = null;
            calculateFees();
            return;
        }

        $.ajax({
            url: '/player-fees/player-details/' + playerId,
            method: 'GET',
            success: function (response) {
                discountSettings = response;
                monthlyFeeSum = 0;

                let options = '<option value="">-- Choose Batch --</option>';
                if (response.batches.length === 0) {
                    toastr.warning('Player has no active batch assignments.');
                } else {
                    response.batches.forEach(function (batch) {
                        options += `<option value="${batch.id}" data-fees="${batch.fees}" data-joined-at="${batch.joined_at}">${batch.name} (${batch.sport} - ${batch.level}) - ₹${batch.fees.toFixed(2)}</option>`;
                    });
                }

                let preselectedBatchId = $('#batch_id').data('preselected');
                $('#batch_id').html(options);
                if (preselectedBatchId) {
                    $('#batch_id').val(preselectedBatchId);
                    $('#batch_id').data('preselected', '');
                }
                $('#batch_id').trigger('change');
                $('#batchSelectContainer').removeClass('d-none');
                calculateFees();
            },
            error: function () {
                toastr.error('Failed to retrieve player batch details.');
            }
        });
    });

    // Batch selection change -> update fee and recalculate
    $(document).on('change', '#batch_id', function () {
        let selectedOption = $(this).find('option:selected');
        let fees = parseFloat(selectedOption.data('fees')) || 0;
        monthlyFeeSum = fees;
        calculateFees();
    });

    // Calculate last day of YYYY-MM month
    function getLastDayOfMonth(monthStr) {
        if (!monthStr) return '';
        let parts = monthStr.split('-');
        let year = parseInt(parts[0]);
        let month = parseInt(parts[1]);
        let lastDay = new Date(year, month, 0).getDate();
        return year + '-' + String(month).padStart(2, '0') + '-' + String(lastDay).padStart(2, '0');
    }

    // Month inputs change triggers
    $(document).on('change', '#startMonth', function () {
        let val = $(this).val();
        if (val) {
            $('#startDate').val(val + '-01');
        } else {
            $('#startDate').val('');
        }
        calculateFees();
    });

    $(document).on('change', '#endMonth', function () {
        let val = $(this).val();
        if (val) {
            let lastDate = getLastDayOfMonth(val);
            $('#endDate').val(lastDate);
        } else {
            $('#endDate').val('');
        }
        calculateFees();
    });

    // Dynamic fee payment calculation (Grace period penalty checks & Discount rules)
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

        // Check if period starts before player joined the batch
        let selectedBatchOption = $('#batch_id').find('option:selected');
        let joinedAtStr = selectedBatchOption.data('joined-at');
        if (joinedAtStr && startVal) {
            let joinedDate = new Date(joinedAtStr);
            let joinedMonthStart = new Date(joinedDate.getFullYear(), joinedDate.getMonth(), 1);
            let startDate = new Date(startVal);
            if (startDate < joinedMonthStart) {
                let formattedJoinedDate = joinedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                $('#joinedDateWarningText').text(`Player joined this batch on ${formattedJoinedDate}. Selected fee period starts before the joining date.`);
                $('#joinedDateWarning').removeClass('d-none');
            } else {
                $('#joinedDateWarning').addClass('d-none');
                $('#joinedDateWarningText').text('');
            }
        } else {
            $('#joinedDateWarning').addClass('d-none');
            $('#joinedDateWarningText').text('');
        }

        let timeDiff = end.getTime() - start.getTime();
        let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

        let durationMonths = Math.round(diffDays / 30.44);
        if (durationMonths < 1) durationMonths = 1;

        $('#calculatedDuration').text(durationMonths + ' Month(s) (' + diffDays + ' Days)');

        let subtotal = monthlyFeeSum * durationMonths;
        let discountValue = 0;
        let totalPenalty = 0;
        let isAnyMonthLate = false;

        // Penalty Late Fee computation
        if (discountSettings && discountSettings.penalty_allow) {
            const penaltyDays = parseInt(discountSettings.penalty_days) || 0;
            const penaltyType = discountSettings.penalty_type || 'fixed';
            const penaltyAmtSetting = parseFloat(discountSettings.penalty_amount) || 0;

            const today = new Date();
            const currentYear = today.getFullYear();
            const currentMonth = today.getMonth() + 1;
            const currentDay = today.getDate();

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

        // Discount calculations (Only applicable if no overdue penalty is active)
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

        let totalAmt = subtotal + totalPenalty - discountAmt;

        $('#sub_totalamount').val(subtotal.toFixed(2));
        $('#penalty_amount').val(totalPenalty.toFixed(2));
        $('#discount_amount').val(discountAmt.toFixed(2));
        $('#total_amt').val(totalAmt.toFixed(2));

        if (totalPenalty > 0) {
            $('#penalty_amount').prop('readonly', false);
        } else {
            $('#penalty_amount').prop('readonly', true);
        }

        // Payment overlap verification
        let playerId = $('#player_id').val();
        let batchId = $('#batch_id').val();
        if (playerId && batchId && startVal && endVal) {
            $.ajax({
                url: '/player-fees/check-overlap',
                method: 'GET',
                data: {
                    player_id: playerId,
                    batch_id: batchId,
                    start_date: startVal,
                    end_date: endVal
                },
                success: function (response) {
                    if (response.overlap) {
                        $('#paymentOverlapWarningText').text(response.message);
                        $('#paymentOverlapWarning').removeClass('d-none');
                        $('#addPlayerFeeForm button[type="submit"]').prop('disabled', true);
                    } else {
                        $('#paymentOverlapWarning').addClass('d-none');
                        $('#paymentOverlapWarningText').text('');

                        let hasJoinedDateWarning = !$('#joinedDateWarning').hasClass('d-none');
                        if (hasJoinedDateWarning) {
                            $('#addPlayerFeeForm button[type="submit"]').prop('disabled', true);
                        } else {
                            $('#addPlayerFeeForm button[type="submit"]').prop('disabled', false);
                        }
                    }
                },
                error: function () {
                    $('#paymentOverlapWarning').addClass('d-none');
                    $('#paymentOverlapWarningText').text('');

                    let hasJoinedDateWarning = !$('#joinedDateWarning').hasClass('d-none');
                    if (hasJoinedDateWarning) {
                        $('#addPlayerFeeForm button[type="submit"]').prop('disabled', true);
                    } else {
                        $('#addPlayerFeeForm button[type="submit"]').prop('disabled', false);
                    }
                }
            });
        } else {
            $('#paymentOverlapWarning').addClass('d-none');
            $('#paymentOverlapWarningText').text('');

            let hasJoinedDateWarning = !$('#joinedDateWarning').hasClass('d-none');
            if (hasJoinedDateWarning) {
                $('#addPlayerFeeForm button[type="submit"]').prop('disabled', true);
            } else {
                $('#addPlayerFeeForm button[type="submit"]').prop('disabled', false);
            }
        }
    }

    // Reset UI calculations values
    function resetCalculation() {
        $('#calculatedDuration').text('0 Month(s)');
        $('#sub_totalamount').val('0.00');
        $('#penalty_amount').val('0.00').prop('readonly', true);
        $('#discount_amount').val('0.00');
        $('#total_amt').val('0.00');
        $('#paymentOverlapWarning').addClass('d-none');
        $('#paymentOverlapWarningText').text('');
        $('#joinedDateWarning').addClass('d-none');
        $('#joinedDateWarningText').text('');
        $('#addPlayerFeeForm button[type="submit"]').prop('disabled', false);
    }

    // Recalculate total dynamically on manual inputs editing
    $(document).on('input', '#sub_totalamount, #discount_amount, #penalty_amount', function () {
        let subtotal = parseFloat($('#sub_totalamount').val()) || 0;
        let discountAmt = parseFloat($('#discount_amount').val()) || 0;
        let penaltyAmt = parseFloat($('#penalty_amount').val()) || 0;
        let totalAmt = subtotal + penaltyAmt - discountAmt;
        if (totalAmt < 0) totalAmt = 0;
        $('#total_amt').val(totalAmt.toFixed(2));
    });

    // Show/Hide transaction fields based on Payment method
    $(document).on('change', '#payment_type', function () {
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

    // Add Player Fee Form Submit
    $(document).on('submit', '#addPlayerFeeForm', function (e) {
        e.preventDefault();

        let hasOverlapWarning = !$('#paymentOverlapWarning').hasClass('d-none');
        let hasJoinedDateWarning = !$('#joinedDateWarning').hasClass('d-none');
        if (hasOverlapWarning || hasJoinedDateWarning) {
            toastr.error('Please resolve the warnings before submitting.');
            return false;
        }

        submitFormAjax(this);
    });

    // Add Fees Generation Form Open
    $(document).on('click', '#addFeesGenerateBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Add Fees Generation Form Submit
    $(document).on('submit', '#addFeesGenerateForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Unpaid Players Dashboard Filter Change Listeners
    $(document).on('change', '#unpaidMonthFilter, #unpaidYearFilter', function () {
        let monthName = $('#unpaidMonthFilter option:selected').text().trim();
        let yearVal = $('#unpaidYearFilter').val();
        $('#unpaidCardTitle').text('Unpaid Players (' + monthName + ' ' + yearVal + ')');

        $('#datatable').DataTable().ajax.reload();
    });

    // Apply visual card body loading state (dimming) on Unpaid Players Dashboard DataTable
    $(document).on('preXhr.dt', '#datatable', function (e, settings, data) {
        $('#unpaidPlayersCard').find('.card-body').css({
            'opacity': '0.5',
            'pointer-events': 'none',
            'transition': 'opacity 0.15s ease'
        });
    });

    // Restore card opacity after DataTable drawing/refreshing finishes
    $(document).on('draw.dt', '#datatable', function () {
        $('#unpaidPlayersCard').find('.card-body').css({
            'opacity': '1',
            'pointer-events': 'auto'
        });
    });

    // Add Player Fee Form Open
    $(document).on('click', '#addPlayerFeeBtn, .collect-fee-btn', function () {
        // Explicitly trigger the Bootstrap offcanvas just in case data attributes didn't fire (e.g. dynamic elements)
        let offcanvasEl = document.getElementById('offcanvasScrolling');
        if (offcanvasEl) {
            let bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
            bsOffcanvas.show();
        }

        openOffcanvasForm($(this).data('url'), $(this).data('title'), function () {
            initDatePicker();
            if ($('#player_id').val()) {
                $('#player_id').trigger('change');
            }
        });
    });

    // Edit Player Fee Form Open
    $(document).on('click', '.edit-fee-btn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit Player Fee Form Submit
    $(document).on('submit', '#editPlayerFeeForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Delete Single Player Fee
    $(document).on('click', '.delete-fee-btn', function () {
        deleteResourceAjax($(this).data('url'), 'This player fee record will be deleted permanently!');
    });

    /* ------------------ SETTINGS MANAGEMENT ------------------- */

    // Toggle Penalty Inputs status/style based on switch
    function togglePenaltyInputs() {
        const isChecked = $('#allow_penalty').is(':checked');
        const $section = $('#penaltyFieldsSection');

        if (isChecked) {
            $section.removeClass('disabled-section');
            $section.find('input, select').prop('disabled', false);
        } else {
            $section.addClass('disabled-section');
            $section.find('input, select').prop('disabled', true);
            $section.find('.text-danger').text('');
        }
    }

    $(document).on('change', '#allow_penalty', function () {
        togglePenaltyInputs();
    });

    // Change icon prefix based on Penalty type select
    $(document).on('change', '#penalty_type', function () {
        const val = $(this).val();
        const $icon = $('#penaltyAmountIcon');
        if (val === 'percentage') {
            $icon.text('%');
        } else {
            $icon.text('₹');
        }
    });

    // Penalty Settings Form Submission
    $(document).on('submit', '#penaltySettingsForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Discount Settings Form Submission
    $(document).on('submit', '#discountSettingsForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Discount Type changes configuration
    function updateDiscountTypeUI() {
        const val = $('#discount_type').val();
        const symbol = val === 'fixed' ? '₹' : '%';
        $('.discount-type-symbol').text(symbol);

        if (val === 'fixed') {
            $('input[name="discount_monthly"]').removeAttr('max');
            $('input[name="discount_quarterly"]').removeAttr('max');
            $('input[name="discount_half_yearly"]').removeAttr('max');
            $('input[name="discount_yearly"]').removeAttr('max');
        } else {
            $('input[name="discount_monthly"]').attr('max', '100');
            $('input[name="discount_quarterly"]').attr('max', '100');
            $('input[name="discount_half_yearly"]').attr('max', '100');
            $('input[name="discount_yearly"]').attr('max', '100');
        }
    }

    $(document).on('change', '#discount_type', function () {
        updateDiscountTypeUI();
    });

    if ($('#discount_type').length > 0) {
        updateDiscountTypeUI();
    }
});
