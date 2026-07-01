// Open form inside Bootstrap Offcanvas using AJAX
function openOffcanvasForm(url, title, onSuccess) {
    $('#offcanvasScrollingLabel').text(title);
    $('#offCanvasContent').html(`
        <div class="modern-loader-container">
            <div class="modern-loader"></div>
            <div class="modern-loader-text">Loading form...</div>
        </div>
    `);

    // Set size class immediately based on URL to prevent slide-in snapping glitch
    let offcanvas = $('#offcanvasScrolling');
    offcanvas.removeClass('offcanvas-wide offcanvas-medium');

    let isSmallUrl = url.includes('/sports') ||
        url.includes('/levels') ||
        url.includes('/expense-category') ||
        url.includes('/roles');

    if (isSmallUrl) {
        offcanvas.addClass('offcanvas-medium');
    } else {
        offcanvas.addClass('offcanvas-wide');
    }

    $.ajax({
        type: 'GET',
        url: url,
        success: function (response) {
            $('#offCanvasContent').html(response);

            // Verify and refine width after load if form has data-width
            let $form = $('#offCanvasContent').find('form');
            if ($form.length) {
                offcanvas.removeClass('offcanvas-wide offcanvas-medium');
                if ($form.attr('data-width') === 'medium') {
                    offcanvas.addClass('offcanvas-medium');
                } else {
                    offcanvas.addClass('offcanvas-wide');
                }
            }

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
        flatpickr("#startTime, #editStartTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false
        });

        flatpickr("#endTime, #editEndTime", {
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
            if (!this._flatpickr && !$(this).hasClass('flatpickr-input')) {
                flatpickr(this, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d M Y",
                    allowInput: true,
                    onReady: function (selectedDates, dateStr, instance) {
                        if (instance.altInput) {
                            $(instance.altInput).removeClass('datepicker-input joined-date-input');
                        }
                    }
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

    // Dropify File Input Initializer
    function initDropify(container) {
        let target = container ? $(container).find('input[type="file"]') : $('input[type="file"]');
        target.each(function () {
            if (!$(this).parent().hasClass('dropify-wrapper')) {
                $(this).addClass('dropify');
                $(this).dropify();
            }
        });
    }

    // Run initial Select2 styling
    initSelect2();
    initFlatpickrDate();
    initDropify();

    // Auto-initialize Select2 on dynamically loaded content (AJAX Complete)
    $(document).ajaxComplete(function () {
        initSelect2('#offCanvasContent');
        initFlatpickrDate('#offCanvasContent');
        initDropify('#offCanvasContent');
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

    // Delete Sports Levels Mapping
    $(document).on('click', '#deleteSportsLevelsBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This Sports Levels mapping will be deleted permanently!');
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
                    <label class="form-label small fw-semibold text-dark mb-1">Sport <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm sport-select" name="assignments[${index}][sport_id]" required>
                        <option value="" disabled selected>Select sport</option>
                        ${sportsOptions}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Level <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm level-select" name="assignments[${index}][level_id]" required disabled>
                        <option value="" disabled selected>Select sport first</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Batch <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm batch-select" name="assignments[${index}][batch_id]" required disabled>
                        <option value="" disabled selected>Select level first</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Joined Date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm datepicker-input joined-date-input" name="assignments[${index}][joined_at]" value="${today}" required>
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

    // Import Player Form Open
    $(document).on('click', '#importPlayerBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Import Players Form Submit
    $(document).on('submit', '#importPlayersForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });



    // Render Excel column mapping selectors and preview rows
    function renderExcelMapping(headers, rows) {
        let $container = $('#excelMappingContainer');
        $container.removeClass('d-none');

        let $headerRow = $('#mappingHeaderRow');
        let $tableBody = $('#previewTableBody');
        $headerRow.empty();
        $tableBody.empty();

        // 1. Row Index Column
        $headerRow.append('<th class="text-secondary fw-bold text-center bg-light" style="width: 50px; border-bottom: 2px solid #e2e8f0;">#</th>');

        // 2. Map selectors for each header
        headers.forEach(function (headerText, index) {
            let colIndex = index;
            let selectedVal = ''; // Default to Select Field
            let isSkipped = false;

            let thHtml = `
                <th class="col-header-container p-3 ${isSkipped ? 'column-skipped' : ''}" data-col-index="${colIndex}" data-html-col-index="${index}" data-header-text="${headerText || ''}" style="min-width: 170px; vertical-align: top; background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <div class="d-flex flex-column align-items-center">
                        <span class="fw-semibold text-secondary mb-2 small text-truncate" style="max-width: 150px; font-size: 0.75rem; letter-spacing: 0.5px;" title="${headerText || ''}">${headerText || ''}</span>
                        <select class="form-select form-select-sm mapping-select shadow-sm" data-col-index="${colIndex}" style="font-size: 0.8rem; border-color: #cbd5e1; border-radius: 6px;">
                            <option value="">Select Field</option>
                            <option value="skip" ${selectedVal === 'skip' ? 'selected' : ''}>skip</option>
                            <option value="firstname" ${selectedVal === 'firstname' ? 'selected' : ''}>First Name</option>
                            <option value="lastname" ${selectedVal === 'lastname' ? 'selected' : ''}>Last Name</option>
                            <option value="email" ${selectedVal === 'email' ? 'selected' : ''}>Email</option>
                            <option value="phone" ${selectedVal === 'phone' ? 'selected' : ''}>Phone</option>
                            <option value="gender" ${selectedVal === 'gender' ? 'selected' : ''}>Gender</option>
                            <option value="joined_at" ${selectedVal === 'joined_at' ? 'selected' : ''}>Joined At</option>
                            <option value="sport" ${selectedVal === 'sport' ? 'selected' : ''}>Sport</option>
                            <option value="level" ${selectedVal === 'level' ? 'selected' : ''}>Level</option>
                            <option value="batch" ${selectedVal === 'batch' ? 'selected' : ''}>Batch</option>
                            <option value="status" ${selectedVal === 'status' ? 'selected' : ''}>Status</option>
                        </select>
                        <a href="javascript:void(0)" class="${isSkipped ? 'text-success' : 'text-danger'} skip-column-btn small fw-semibold text-decoration-none mt-2" data-col-index="${colIndex}">${isSkipped ? 'unskip' : 'skip'}</a>
                    </div>
                </th>
            `;
            $headerRow.append(thHtml);
        });

        // 3. Render preview data rows
        rows.forEach(function (row, rowIndex) {
            let trHtml = `<tr><td class="text-muted text-center fw-semibold small bg-light" style="width: 50px; border-bottom: 1px solid #f1f5f9;">${rowIndex + 1}</td>`;
            headers.forEach(function (header, index) {
                let cellValue = row[index] !== undefined && row[index] !== null ? row[index] : '';
                trHtml += `<td class="small px-3 py-2.5 text-secondary" style="border-bottom: 1px solid #f1f5f9;">${cellValue}</td>`;
            });
            trHtml += '</tr>';
            $tableBody.append(trHtml);
        });
    }

    // Toggle column skip status visually and functionally
    function toggleColumnSkip(colIndex, isSkipped) {
        let $header = $(`.col-header-container[data-col-index="${colIndex}"]`);
        let $select = $header.find('.mapping-select');

        if (isSkipped) {
            $select.val('skip').trigger('change');
        } else {
            $select.val('').trigger('change');
        }
    }

    // Intercept and handle Read Import Players Form Submit (Step 1)
    $(document).on('submit', '#readImportPlayersForm', function (e) {
        e.preventDefault();

        let $form = $(this);
        let formEl = $form[0];
        let formData = new FormData(formEl);
        let url = $form.attr('action');
        let submitBtn = $form.find('#submitImportBtn');
        let originalHtml = submitBtn.html();

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Reading Excel...');
        $form.find('#fileError').text('');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                submitBtn.prop('disabled', false).html(originalHtml);

                if (response.success) {
                    // Store import token
                    // $('#importToken').val(response.import_token);

                    // Render Excel Mapping directly below the button
                    renderExcelMapping(response.headers, response.rows);
                } else {
                    toastr.error(response.message || 'Failed to read Excel file.');
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html(originalHtml);
                let msg = xhr.responseJSON?.message || 'Error reading Excel file.';
                toastr.error(msg);
                $('#fileError').text(msg);
            }
        });
    });

    // Skip column button click handler
    $(document).on('click', '.skip-column-btn', function () {
        let colIndex = $(this).data('col-index');
        let $header = $(`.col-header-container[data-col-index="${colIndex}"]`);
        let $select = $header.find('.mapping-select');
        let isSkipped = $select.val() === 'skip';

        if (isSkipped) {
            $select.val('').trigger('change');
        } else {
            $select.val('skip').trigger('change');
        }
    });

    // Sync dropdown changes to styling and buttons
    $(document).on('change', '.mapping-select', function () {
        let colIndex = $(this).data('col-index');
        let val = $(this).val();
        let $header = $(`.col-header-container[data-col-index="${colIndex}"]`);
        let htmlColIndex = $header.data('html-col-index');
        let $btn = $header.find('.skip-column-btn');

        if (val === 'skip') {
            $btn.text('unskip').removeClass('text-danger').addClass('text-success');
            $header.addClass('column-skipped');
            $(`#excelPreviewTable tbody tr`).each(function () {
                $(this).find(`td:eq(${htmlColIndex + 1})`).addClass('column-skipped');
            });
        } else {
            $btn.text('skip').removeClass('text-success').addClass('text-danger');
            $header.removeClass('column-skipped');
            $(`#excelPreviewTable tbody tr`).each(function () {
                $(this).find(`td:eq(${htmlColIndex + 1})`).removeClass('column-skipped');
            });
        }
    });

    // Clear preview button click handler
    $(document).on('click', '#clearPreviewBtn', function () {
        $('#importToken').val('');
        $('#excelMappingContainer').addClass('d-none');
        let dropifyEl = $('#importFile').data('dropify');
        if (dropifyEl) {
            dropifyEl.clearElement();
        }
    });

    function getFieldLabel(field) {
        let labels = {
            'firstname': 'First Name',
            'lastname': 'Last Name',
            'email': 'Email',
            'phone': 'Phone',
            'gender': 'Gender',
            'joined_at': 'Joined At',
            'sport': 'Sport',
            'level': 'Level',
            'batch': 'Batch',
            'status': 'Status'
        };
        return labels[field] || field;
    }

    // Save dynamic mapping import (Step 2)
    $(document).on('click', '#saveImportBtn', function () {
        let mappings = {};
        let mappedFields = new Set();
        let hasDuplicates = false;
        let duplicateField = '';
        let hasUnmapped = false;

        $('.mapping-select').each(function () {
            let colIndex = $(this).data('col-index');
            let val = $(this).val();

            if (!val) {
                hasUnmapped = true;
            } else {
                if (val !== 'skip') {
                    if (mappedFields.has(val)) {
                        hasDuplicates = true;
                        duplicateField = val;
                    }
                    mappedFields.add(val);
                }
                mappings[colIndex] = val;
            }
        });

        // 1. Give error if user didn't select mapping/skip for any column
        if (hasUnmapped) {
            toastr.error('Please map every column to a database field or select "skip".');
            return;
        }

        // 2. Give error if duplicate field mappings are found
        if (hasDuplicates) {
            toastr.error('Field "' + getFieldLabel(duplicateField) + '" is mapped multiple times. Each field can only be mapped to one column.');
            return;
        }

        // 3. Validate required fields mapping
        let required = ['firstname', 'lastname', 'phone', 'joined_at'];
        let missing = [];
        required.forEach(function (req) {
            if (!mappedFields.has(req)) {
                missing.push(getFieldLabel(req));
            }
        });

        if (missing.length > 0) {
            toastr.error('The following required fields must be mapped: ' + missing.join(', ') + '.');
            return;
        }

        // 4. Construct table-wise player data (skipping columns mapped to 'skip')
        let playersData = [];
        $('#previewTableBody tr').each(function () {
            let $row = $(this);
            let playerObj = {};
            let hasAnyValue = false;

            $('.mapping-select').each(function (index) {
                let val = $(this).val(); // e.g. "firstname", "lastname", "skip"
                if (val && val !== 'skip') {
                    // Cell index in row is index + 1 (since index 0 is '#' column)
                    let cellVal = $row.find(`td:eq(${index + 1})`).text().trim();
                    if (cellVal !== '') {
                        hasAnyValue = true;
                    }

                    let key = val;
                    if (val === 'firstname') key = 'first_name';
                    if (val === 'lastname') key = 'last_name';

                    playerObj[key] = cellVal;
                }
            });

            if (hasAnyValue) {
                playersData.push(playerObj);
            }
        });

        let importUrl = $('#importUrl').val();
        let token = $('input[name="_token"]').val();

        let saveBtn = $(this);
        let originalText = saveBtn.html();
        saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url: importUrl,
            method: 'POST',
            data: {
                _token: token,
                players: playersData
            },
            success: function (response) {
                toastr.success(response.message || 'Import process completed.');
                closeOffcanvasForm();
                if ($('#datatable').length) {
                    $('#datatable').DataTable().ajax.reload();
                }

                // Show detailed modal results
                $('#importSuccessCount').text(response.summary.imported);
                $('#importSkippedCount').text(response.summary.skipped);
                $('#importTotalCount').text(response.summary.total);

                let $errList = $('#importErrorsList');
                $errList.empty();
                if (response.errors && response.errors.length > 0) {
                    response.errors.forEach(function (err) {
                        $errList.append(`<li><i class="bi bi-dot"></i> ${err}</li>`);
                    });
                    $('#importErrorsContainer').removeClass('d-none');
                } else {
                    $('#importErrorsContainer').addClass('d-none');
                }

                let myModal = new bootstrap.Modal(document.getElementById('importResultsModal'));
                myModal.show();
            },
            error: function (xhr) {
                saveBtn.prop('disabled', false).html(originalText);
                toastr.error(xhr.responseJSON?.message || 'Failed to import players.');
            }
        });
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
        if (totalPenalty > 0) {
            if (discountSettings.penalty_type == 'fixed') {
                $('#penalty_num').text('(' + discountSettings.penalty_amount + ' ₹ )');
            } else {
                $('#penalty_num').text('(' + discountSettings.penalty_amount + ' % )');
            }
        } else {
            $('#penalty_num').text('');
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



    // --------------------Expense Category-----------------------

    // Add expense category Form Open
    $(document).on('click', '#addExpenseCategoryBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Add expense category Form Submit
    $(document).on('submit', '#addExpenseCategoryForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Edit expense category Form Open
    $(document).on('click', '#editExpenseCategoryBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit expense category Form Submit
    $(document).on('submit', '#editExpenseCategoryForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Delete Single expense category
    $(document).on('click', '#deleteExpenseCategoryBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This Expense Category will be deleted permanently!');
    });

    Bulkdelete('expense-category', '.user-checkbox');
    BulkUpdateStatus('expense-category', '.user-checkbox');

    // --------------------Expenses Management-----------------------

    // Add Expense Form Open
    $(document).on('click', '#addExpenseBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Add Expense Form Submit
    $(document).on('submit', '#addExpenseForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Edit Expense Form Open
    $(document).on('click', '#editExpenseBtn', function () {
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit Expense Form Submit
    $(document).on('submit', '#editExpenseForm', function (e) {
        e.preventDefault();
        submitFormAjax(this);
    });

    // Delete Single Expense
    $(document).on('click', '#deleteExpenseBtn', function () {
        deleteResourceAjax($(this).data('url'), 'This Expense record will be deleted permanently!');
    });

    Bulkdelete('expenses', '.user-checkbox');

    /* -------------------- ROLE MANAGEMENT -------------------- */

    // Add Role Form Open
    $(document).on('click', '#addRoleBtn', function (e) {
        e.preventDefault();
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Helper to refresh only the roles grid using AJAX to prevent whole window reloads
    function refreshRolesGrid() {
        $.ajax({
            url: window.location.href,
            type: 'GET',
            success: function (data) {
                let newGridHtml = $(data).find('#rolesGrid').html();
                $('#rolesGrid').html(newGridHtml);
            },
            error: function () {
                toastr.error('Failed to refresh roles grid.');
            }
        });
    }

    // Add Role Form Submit
    $(document).on('submit', '#addRoleForm', function (e) {
        e.preventDefault();
        submitFormAjax(this, function () {
            refreshRolesGrid();
        });
    });

    // Edit Role Form Open
    $(document).on('click', '#editRoleBtn', function (e) {
        e.preventDefault();
        openOffcanvasForm($(this).data('url'), $(this).data('title'));
    });

    // Edit Role Form Submit
    $(document).on('submit', '#editRoleForm', function (e) {
        e.preventDefault();
        submitFormAjax(this, function () {
            refreshRolesGrid();
        });
    });

    // Delete Role
    $(document).on('click', '#deleteRoleBtn', function (e) {
        e.preventDefault();
        deleteResourceAjax($(this).data('url'), 'This Role will be deleted permanently!', function () {
            refreshRolesGrid();
        });
    });

    /* ------------------ ROLE PERMISSIONS AJAX ------------------ */


    // Handle View Permission Click
    $(document).on('click', '.view-permissions-btn', function (e) {
        e.preventDefault();

        let btn = $(this);
        let url = btn.data('url');
        let roleId = btn.data('role-id');



        // Remove selection highlight from all cards, add to selected card
        $('.role-card').css('border', 'none');
        $('#roleCard_' + roleId).css('border', '2px solid #7c5cff');


        // Show loading placeholder inside permissions container
        $('#permissionsContainer').html(`
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body p-5 text-center">
                    <div class="spinner-border text-primary" role="status" style="color: #7c5cff !important;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-secondary mt-2 mb-0">Loading permissions...</p>
                </div>
            </div>
        `);

        // Fetch permissions via AJAX
        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                $('#permissionsContainer').html(response);
            },
            error: function (xhr) {
                toastr.error('Failed to load permissions.');
                $('#permissionsContainer').html('');
            }
        });
    });

    // Handle Permissions Form Submission via AJAX
    $(document).on('submit', '#savePermissionsForm', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Disable button and show spinner
        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...'
        );

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (response) {
                toastr.success('Permissions updated successfully.');
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            },
            error: function (xhr) {
                toastr.error('Failed to update permissions.');
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });

    // Select All Fields handler inside any export modal
    $(document).on('change', '.select-all-fields', function () {
        const modal = $(this).closest('.modal');
        modal.find('.field-checkbox').prop('checked', this.checked);
    });

    $(document).on('change', '.field-checkbox', function () {
        const modal = $(this).closest('.modal');
        const total = modal.find('.field-checkbox').length;
        const checked = modal.find('.field-checkbox:checked').length;
        modal.find('.select-all-fields').prop('checked', total === checked);
    });

    // Form submit for export modals (direct submit without AJAX)
    $(document).on('submit', '.export-modal-form', function (e) {
        const form = $(this);
        const modal = form.closest('.modal');

        if (form.find('.field-checkbox:checked').length === 0) {
            e.preventDefault();
            toastr.error('Please select at least one field to export.');
            return;
        }

        setTimeout(() => {
            modal.modal('hide');
        }, 300);
    });

    // Intercept and handle vertical stacked Excel import preview (Step 1)
    $(document).on('submit', '#verticalImportForm', function (e) {
        e.preventDefault();

        let $form = $(this);
        let formEl = $form[0];
        let formData = new FormData(formEl);
        let url = $form.attr('action');
        let submitBtn = $form.find('#submitImportBtn');
        let originalHtml = submitBtn.html();

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Reading Excel...');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                submitBtn.prop('disabled', false).html(originalHtml);

                if (response.success) {
                    toastr.success('Excel file read successfully!');
                    renderHorizontalMappingPreview(response.headers, response.rows, response.schema);
                } else {
                    toastr.error(response.message || 'Failed to read Excel file.');
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html(originalHtml);
                let msg = xhr.responseJSON?.message || 'Error reading Excel file.';
                toastr.error(msg);
            }
        });
    });

    // Automatically find a suitable database mapping field based on the Excel header text
    function findSuitableMapping(headerText) {
        if (!headerText) return '';
        headerText = headerText.trim().toLowerCase();

        // Pattern to match "[Entity] - column" (e.g. "[Sports] - name")
        let match = headerText.match(/^\[([^\]]+)\]\s*-\s*(.+)$/i);
        if (match) {
            let entity = match[1].trim().toLowerCase();
            let column = match[2].trim().toLowerCase();

            let prefix = '';
            if (entity === 'sports') prefix = 'sport';
            else if (entity === 'levels') prefix = 'level';
            else if (entity === 'sport levels') prefix = 'sport_level';
            else if (entity === 'expense categories') prefix = 'exp_cat';
            else if (entity === 'batches') prefix = 'batch';
            else if (entity === 'users') prefix = 'user';
            else if (entity === 'expenses') prefix = 'expense';
            else if (entity === 'players') prefix = 'player';

            if (prefix) {
                let candidate = column;
                let expectedPrefix = prefix + '_';
                if (!candidate.startsWith(expectedPrefix) && candidate !== prefix) {
                    candidate = expectedPrefix + candidate;
                }
                return candidate;
            }
        }

        // Fallback: clean header string and match
        return headerText.replace(/[^a-z0-9_]/g, '_').replace(/_+/g, '_');
    }

    // Render Excel column mapping selectors and preview rows horizontally (stacked dataset preview)
    function renderHorizontalMappingPreview(headers, rows, schema) {
        let container = $('#importExportContainer');
        container.empty();

        let thsHtml = '';
        headers.forEach(function (headerText, index) {
            let colIndex = index;
            let bestMatch = findSuitableMapping(headerText);

            // Dynamically build the select field option group list
            let selectOptionsHtml = `
                <option value="">Select Field</option>
                <option value="skip" ${bestMatch === 'skip' ? 'selected' : ''}>skip</option>
            `;

            if (schema) {
                Object.keys(schema).forEach(function (groupKey) {
                    let groupObj = schema[groupKey];
                    selectOptionsHtml += `<optgroup label="${groupObj.label}">`;

                    Object.keys(groupObj.fields).forEach(function (fieldKey) {
                        let fieldLabel = groupObj.fields[fieldKey];
                        let prefix = groupObj.prefix;

                        let optionValue = fieldKey;
                        if (prefix) {
                            let expectedPrefix = prefix + '_';
                            if (!optionValue.startsWith(expectedPrefix)) {
                                optionValue = expectedPrefix + optionValue;
                            }
                        }

                        let isSelected = (optionValue === bestMatch) ? 'selected' : '';
                        selectOptionsHtml += `<option value="${optionValue}" ${isSelected}>${fieldLabel}</option>`;
                    });

                    selectOptionsHtml += `</optgroup>`;
                });
            }

            thsHtml += `
                <th class="col-header-container p-3" data-col-index="${colIndex}" data-html-col-index="${index}" data-header-text="${headerText || ''}" style="min-width: 170px; vertical-align: top; background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <div class="d-flex flex-column align-items-center">
                        <span class="fw-semibold text-secondary mb-2 small text-truncate" style="max-width: 150px; font-size: 0.75rem; letter-spacing: 0.5px;" title="${headerText || ''}">${headerText || ''}</span>
                        <select class="form-select form-select-sm mapping-select shadow-sm" data-col-index="${colIndex}" style="font-size: 0.8rem; border-color: #cbd5e1; border-radius: 6px;">
                            ${selectOptionsHtml}
                        </select>
                        <a href="javascript:void(0)" class="text-danger skip-column-btn small fw-semibold text-decoration-none mt-2" data-col-index="${colIndex}">skip</a>
                    </div>
                </th>
            `;
        });

        let trsHtml = '';
        rows.forEach(function (row, rowIndex) {
            trsHtml += `<tr><td class="text-muted text-center fw-semibold small bg-light" style="width: 50px; border-bottom: 1px solid #f1f5f9;">${rowIndex + 1}</td>`;
            headers.forEach(function (header, index) {
                let cellValue = row[index] !== undefined && row[index] !== null ? row[index] : '';
                trsHtml += `<td class="small px-3 py-2.5 text-secondary" style="border-bottom: 1px solid #f1f5f9;">${cellValue}</td>`;
            });
            trsHtml += '</tr>';
        });

        let previewHtml = `
            <div class="card border-0 shadow-sm rounded-4 mt-4 animate__animated animate__fadeInUp">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0 rounded-top-4">
                    <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.15rem;">
                        Excel Data
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light px-3 py-1.5 fw-semibold border rounded-3" id="clearVerticalPreviewBtn" style="font-size: 0.875rem;">
                            Clear
                        </button>
                        <button type="button" class="btn btn-primary px-4 py-1.5 fw-semibold shadow-sm rounded-3" id="saveVerticalImportBtn" style="background-color: #4f46e5; border-color: #4f46e5; font-size: 0.875rem;">
                            Save
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 rounded-bottom-4">
                    <div class="table-responsive" style="max-height: 450px; overflow: auto;">
                        <table class="table table-hover align-middle mb-0 text-nowrap" id="excelPreviewTable">
                            <thead>
                                <tr id="mappingHeaderRow">
                                    <th class="text-secondary fw-bold text-center bg-light" style="width: 50px; border-bottom: 2px solid #e2e8f0;">#</th>
                                    ${thsHtml}
                                </tr>
                            </thead>
                            <tbody id="previewTableBody">
                                ${trsHtml}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        container.html(previewHtml);
    }

    // Save vertical import button handler
    $(document).on('click', '#saveVerticalImportBtn', function () {
        let sportsData = [];
        let levelsData = [];
        let sportLevelsData = [];
        let expCatsData = [];
        let batchesData = [];
        let usersData = [];
        let expensesData = [];
        let playersData = [];

        let hasAnyMapping = false;
        $('.mapping-select').each(function () {
            let val = $(this).val();
            if (val && val !== 'skip') {
                hasAnyMapping = true;
            }
        });

        if (!hasAnyMapping) {
            toastr.error('Please map at least one column to save.');
            return;
        }

        $('#previewTableBody tr').each(function () {
            let $row = $(this);
            
            let sportObj = {}, hasSport = false;
            let levelObj = {}, hasLevel = false;
            let sportLevelObj = {}, hasSportLevel = false;
            let expCatObj = {}, hasExpCat = false;
            let batchObj = {}, hasBatch = false;
            let userObj = {}, hasUser = false;
            let expenseObj = {}, hasExpense = false;
            let playerObj = {}, hasPlayer = false;

            $('.mapping-select').each(function (index) {
                let val = $(this).val(); // e.g. "sport_name", "player_phone", "skip", ""
                if (val && val !== 'skip') {
                    let cellVal = $row.find(`td:eq(${index + 1})`).text().trim();
                    
                    if (val.startsWith('sport_level_')) {
                        let field = val.replace('sport_level_', '');
                        sportLevelObj[field] = cellVal;
                        if (cellVal !== '') hasSportLevel = true;
                    } else if (val.startsWith('sport_')) {
                        let field = val.replace('sport_', '');
                        sportObj[field] = cellVal;
                        if (cellVal !== '') hasSport = true;
                    } else if (val.startsWith('level_')) {
                        let field = val.replace('level_', '');
                        levelObj[field] = cellVal;
                        if (cellVal !== '') hasLevel = true;
                    } else if (val.startsWith('exp_cat_')) {
                        let field = val.replace('exp_cat_', '');
                        expCatObj[field] = cellVal;
                        if (cellVal !== '') hasExpCat = true;
                    } else if (val.startsWith('batch_')) {
                        let field = val.replace('batch_', '');
                        batchObj[field] = cellVal;
                        if (cellVal !== '') hasBatch = true;
                    } else if (val.startsWith('user_')) {
                        let field = val.replace('user_', '');
                        userObj[field] = cellVal;
                        if (cellVal !== '') hasUser = true;
                    } else if (val.startsWith('expense_')) {
                        let field = val.replace('expense_', '');
                        expenseObj[field] = cellVal;
                        if (cellVal !== '') hasExpense = true;
                    } else if (val.startsWith('player_')) {
                        let field = val.replace('player_', '');
                        playerObj[field] = cellVal;
                        if (cellVal !== '') hasPlayer = true;
                    }
                }
            });

            if (hasSport) sportsData.push(sportObj);
            if (hasLevel) levelsData.push(levelObj);
            if (hasSportLevel) sportLevelsData.push(sportLevelObj);
            if (hasExpCat) expCatsData.push(expCatObj);
            if (hasBatch) batchesData.push(batchObj);
            if (hasUser) usersData.push(userObj);
            if (hasExpense) expensesData.push(expenseObj);
            if (hasPlayer) playersData.push(playerObj);
        });

        let saveBtn = $(this);
        let originalHtml = saveBtn.html();
        saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url: '/settings/import',
            method: 'POST',
            data: {
                _token: $('#exportCsrfToken').val(),
                sports: sportsData,
                levels: levelsData,
                sport_levels: sportLevelsData,
                expense_categories: expCatsData,
                batches: batchesData,
                users: usersData,
                expenses: expensesData,
                players: playersData
            },
            success: function (response) {
                saveBtn.prop('disabled', false).html(originalHtml);
                if (response.success) {
                    toastr.success(response.message || 'Data imported successfully!');
                    $('#clearVerticalPreviewBtn').trigger('click');

                    // Show detailed modal results
                    $('#importSuccessCount').text(response.summary.imported);
                    $('#importSkippedCount').text(response.summary.skipped);
                    $('#importTotalCount').text(response.summary.total);

                    let $errList = $('#importErrorsList');
                    $errList.empty();
                    if (response.errors && response.errors.length > 0) {
                        response.errors.forEach(function (err) {
                            $errList.append(`<li><i class="bi bi-dot"></i> ${err}</li>`);
                        });
                        $('#importErrorsContainer').removeClass('d-none');
                    } else {
                        $('#importErrorsContainer').addClass('d-none');
                    }

                    let myModal = new bootstrap.Modal(document.getElementById('importResultsModal'));
                    myModal.show();
                } else {
                    toastr.error(response.message || 'Failed to import data.');
                }
            },
            error: function (xhr) {
                saveBtn.prop('disabled', false).html(originalHtml);
                let msg = xhr.responseJSON?.message || 'Error importing data.';
                toastr.error(msg);
            }
        });
    });

    // Clear vertical preview button handler
    $(document).on('click', '#clearVerticalPreviewBtn', function () {
        // Reset file input
        let dropifyEl = $('#importFile').data('dropify');
        if (dropifyEl) {
            dropifyEl.clearElement();
        }

        // Restore original instructions template
        let container = $('#importExportContainer');
        container.html(`
            <div class="card border-0 shadow-sm rounded-4 mt-4 animate__animated animate__fadeInUp">
                <div class="card-header border-0 pt-4 pb-2 px-4 bg-white rounded-top-4">
                    <h6 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Expected File Structure (Vertical Stack)
                    </h6>
                    <p class="text-secondary small mb-3">
                        Your Excel file must contain all table data vertically stacked in the first sheet. Separate each table section using a blank row and identify each section with its bracketed header as shown below:
                    </p>
                </div>
                <div class="card-body px-4 pb-4 pt-0 bg-white rounded-bottom-4">
                    <!-- Instruction Steps -->
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-2 small text-secondary">
                            <span class="badge bg-primary me-2">1</span>
                            <span>Each model section starts with a section header in brackets, e.g., <code>[Sports]</code> in the first cell of a row.</span>
                        </div>
                        <div class="d-flex align-items-start mb-2 small text-secondary">
                            <span class="badge bg-primary me-2">2</span>
                            <span>The immediate next row contains the column field names (comma-separated).</span>
                        </div>
                        <div class="d-flex align-items-start mb-2 small text-secondary">
                            <span class="badge bg-primary me-2">3</span>
                            <span>Subsequent rows contain the actual records to import.</span>
                        </div>
                        <div class="d-flex align-items-start mb-2 small text-secondary">
                            <span class="badge bg-primary me-2">4</span>
                            <span>Leave a single empty/blank row before starting the next model section.</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-dark small" style="letter-spacing: 0.5px; text-transform: uppercase;">Supported Models &amp; Fields</h6>

                    <div class="row g-3">
                        <!-- Sports -->
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <div class="fw-bold text-primary mb-1"><code>[Sports]</code></div>
                                <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>name, description, status</code></div>
                                <div class="small text-muted italic">E.g., Football, Football Academy, active</div>
                            </div>
                        </div>

                        <!-- Levels -->
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <div class="fw-bold text-success mb-1"><code>[Levels]</code></div>
                                <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>name, status</code></div>
                                <div class="small text-muted italic">E.g., Beginner, active</div>
                            </div>
                        </div>

                        <!-- Sport Levels -->
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <div class="fw-bold text-warning mb-1"><code>[Sport Levels]</code></div>
                                <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>sport, level, fees</code></div>
                                <div class="small text-muted italic">E.g., Football, Beginner, 500.00</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="small text-secondary">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            You can download the template by clicking <strong>Sample File</strong> in the Import card above to get a complete reference.
                        </span>
                    </div>
                </div>
            </div>
        `);
    });

    // Dynamically loaded schema from backend used instead of hardcoded config

    // Keep standard template HTML in a helper variable to clear/cancel cleanly
    const getInstructionsTemplateHtml = () => `
        <div class="card border-0 shadow-sm rounded-4 mt-4 animate__animated animate__fadeInUp">
            <div class="card-header border-0 pt-4 pb-2 px-4 bg-white rounded-top-4">
                <h6 class="fw-bold mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Expected File Structure (Vertical Stack)
                </h6>
                <p class="text-secondary small mb-3">
                    Your Excel file must contain all table data vertically stacked in the first sheet. Separate each table section using a blank row and identify each section with its bracketed header as shown below:
                </p>
            </div>
            <div class="card-body px-4 pb-4 pt-0 bg-white rounded-bottom-4">
                <!-- Instruction Steps -->
                <div class="mb-4">
                    <div class="d-flex align-items-start mb-2 small text-secondary">
                        <span class="badge bg-primary me-2">1</span>
                        <span>Each model section starts with a section header in brackets, e.g., <code>[Sports]</code> in the first cell of a row.</span>
                    </div>
                    <div class="d-flex align-items-start mb-2 small text-secondary">
                        <span class="badge bg-primary me-2">2</span>
                        <span>The immediate next row contains the column field names (comma-separated).</span>
                    </div>
                    <div class="d-flex align-items-start mb-2 small text-secondary">
                        <span class="badge bg-primary me-2">3</span>
                        <span>Subsequent rows contain the actual records to import.</span>
                    </div>
                    <div class="d-flex align-items-start mb-2 small text-secondary">
                        <span class="badge bg-primary me-2">4</span>
                        <span>Leave a single empty/blank row before starting the next model section.</span>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 text-dark small" style="letter-spacing: 0.5px; text-transform: uppercase;">Supported Models &amp; Fields</h6>

                <div class="row g-3">
                    <!-- Sports -->
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <div class="fw-bold text-primary mb-1"><code>[Sports]</code></div>
                            <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>name, description, status</code></div>
                            <div class="small text-muted italic">E.g., Football, Football Academy, active</div>
                        </div>
                    </div>

                    <!-- Levels -->
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <div class="fw-bold text-success mb-1"><code>[Levels]</code></div>
                            <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>name, status</code></div>
                            <div class="small text-muted italic">E.g., Beginner, active</div>
                        </div>
                    </div>

                    <!-- Sport Levels -->
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <div class="fw-bold text-warning mb-1"><code>[Sport Levels]</code></div>
                            <div class="small text-secondary mb-2"><strong>Columns:</strong> <code>sport, level, fees</code></div>
                            <div class="small text-muted italic">E.g., Football, Beginner, 500.00</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <span class="small text-secondary">
                        <i class="bi bi-info-circle me-1 text-primary"></i>
                        You can download the template by clicking <strong>Sample File</strong> in the Import card above to get a complete reference.
                    </span>
                </div>
            </div>
        </div>
    `;

    // Show export fields selection view
    $(document).on('click', '#showExportFieldsBtn', function () {
        let container = $('#importExportContainer');

        // Show loading spinner
        container.html(`
            <div class="card border-0 shadow-sm rounded-4 mt-4 animate__animated animate__fadeInUp" id="exportLoadingCard">
                <div class="card-body p-5 text-center bg-white rounded-4">
                    <div class="spinner-border text-primary" role="status" style="color: #4f46e5 !important;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-secondary mt-2 mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">Loading export selection form...</p>
                </div>
            </div>
        `);

        // Fetch form from backend
        $.ajax({
            url: '/settings/export-fields',
            method: 'GET',
            success: function (html) {
                container.html(html);
                $('html, body').animate({
                    scrollTop: $("#exportSelectionCard").offset().top - 20
                }, 500);
            },
            error: function (xhr) {
                toastr.error('Failed to load export options form.');
                container.html(getInstructionsTemplateHtml());
            }
        });
    });

    // Cancel selection handler
    $(document).on('click', '#cancelExportSelectionBtn', function () {
        $('#importExportContainer').html(getInstructionsTemplateHtml());
    });

    // Global Select All handler
    $(document).on('change', '#globalSelectAllFields', function () {
        const checked = this.checked;
        const form = $('#customExportFieldsForm');
        form.find('.model-select-all').prop('checked', checked);
        form.find('.column-checkbox').prop('checked', checked);
    });

    // Model Select All handler
    $(document).on('change', '.model-select-all', function () {
        const model = $(this).data('model');
        const checked = this.checked;
        const form = $('#customExportFieldsForm');
        form.find(`.column-checkbox[data-model="${model}"]`).prop('checked', checked);

        // Update global select all checkbox
        updateGlobalCheckbox();
    });

    // Individual Column Checkbox handler
    $(document).on('change', '.column-checkbox', function () {
        const model = $(this).data('model');
        const form = $('#customExportFieldsForm');

        const totalInModel = form.find(`.column-checkbox[data-model="${model}"]`).length;
        const checkedInModel = form.find(`.column-checkbox[data-model="${model}"]:checked`).length;

        form.find(`#model_all_${model}`).prop('checked', totalInModel === checkedInModel);

        // Update global select all checkbox
        updateGlobalCheckbox();
    });

    // Helper to update global select all state
    function updateGlobalCheckbox() {
        const form = $('#customExportFieldsForm');
        const total = form.find('.column-checkbox').length;
        const checked = form.find('.column-checkbox:checked').length;
        $('#globalSelectAllFields').prop('checked', total === checked);
    }

    // Export Form Submission handler
    $(document).on('submit', '#customExportFieldsForm', function (e) {
        const checkedCount = $(this).find('.column-checkbox:checked').length;
        if (checkedCount === 0) {
            e.preventDefault();
            toastr.error('Please select at least one column to export.');
            return false;
        }
    });

});

