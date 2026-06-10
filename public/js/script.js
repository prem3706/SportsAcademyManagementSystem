$(document).ready(function () {

    // SweetAlert Configuration
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-danger mx-2",
            cancelButton: "btn btn-secondary"
        },
        buttonsStyling: false
    });

    // Toastr Configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 3000
    };

    //Time Picker Initialization
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

    // Initialize Select2
    function initSelect2() {

        $('.select2').each(function () {

            if (!$(this).hasClass('select2-hidden-accessible')) {

                $(this).select2({

                    width: '100%',

                    dropdownParent: $('#offcanvasScrolling')

                });

            }

        });

    }


    // CSRF TOKEN
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Role Change
    $(document).on('change', '#role', function () {

        let selectedRole = $(this).val();

        if (selectedRole === 'coach') {

            $('#joining_date_div').hide();

        } else {

            $('#joining_date_div').show();
        }
    });

    $(document).on('click', '#togglePassword', function () {

        const passwordField = $('#password');
        const icon = $('#toggleIcon');

        // Check current type attribute
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';

        // Toggle input type and icon classes
        passwordField.attr('type', type);
        icon.toggleClass('bi-eye-slash bi-eye');
    });

    // User DataTable Filters
    $('#datatable').on('preXhr.dt', function (e, settings, data) {

        // User Filters
        data.status = $('#statusFilter').val();
        data.role = $('#roleFilter').val();

        // Player / Player Fees Filters
        data.sport = $('#sportFilter').val();
        data.level = $('#levelFilter').val();
        data.batch = $('#batchFilter').val();
        data.month = $('#monthFilter').val();
        data.year = $('#yearFilter').val();
        data.payment_type = $('#paymentTypeFilter').val();
    });


    $(document).on('change',
        '#statusFilter, #roleFilter, #sportFilter, #levelFilter, #batchFilter, #monthFilter, #yearFilter, #paymentTypeFilter',
        function () {

            $('#datatable').DataTable().ajax.reload();

            checkRefreshButton();
        }
    );

    // Refresh Button


    $(document).on('click', '#refreshTableBtn', function () {

        // Reset All Filters
        $('#statusFilter').val('');
        $('#roleFilter').val('');

        $('#sportFilter').val('');
        $('#levelFilter').val('');
        $('#batchFilter').val('');
        $('#monthFilter').val('');
        $('#yearFilter').val('');
        $('#paymentTypeFilter').val('');

        // Reload Table
        $('#datatable').DataTable().ajax.reload();

        // Hide Refresh Button
        $(this).addClass('d-none');
    });

    function checkRefreshButton() {

        let status = $('#statusFilter').val();
        let role = $('#roleFilter').val();

        let sport = $('#sportFilter').val();
        let level = $('#levelFilter').val();
        let batch = $('#batchFilter').val();
        let month = $('#monthFilter').val();
        let year = $('#yearFilter').val();
        let payment_type = $('#paymentTypeFilter').val();

        if (
            status !== '' ||
            role !== '' ||
            sport !== '' ||
            level !== '' ||
            batch !== '' ||
            month !== '' ||
            year !== '' ||
            payment_type !== ''
        ) {

            $('#refreshTableBtn').removeClass('d-none');

        } else {

            $('#refreshTableBtn').addClass('d-none');
        }
    }

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

    // Select All Checkbox
    $(document).on('change', '#select-all', function () {

        $('.user-checkbox').prop('checked', this.checked);

        toggleBulkButton();
    });

    // Single Checkbox
    $(document).on('change', '.user-checkbox', function () {

        let total = $('.user-checkbox').length;
        let checked = $('.user-checkbox:checked').length;

        $('#select-all').prop('checked', total === checked);

        toggleBulkButton();
    });

    // Reset Checkbox on Table Reload
    $('#users-table').on('draw.dt', function () {

        $('#select-all').prop('checked', false);

        toggleBulkButton();
    });

    // Bulk Delete


    function Bulkdelete(name, checkboxClass = '.user-checkbox') {
        $('#bulkDeleteBtn').off('click').on('click', function () {

            let ids = [];
            let url = $(this).data('url');

            $(`${checkboxClass}:checked`).each(function () {
                ids.push($(this).val());
            });

            // No Selection
            if (ids.length === 0) {
                toastr.warning(`Please select at least one ${name}.`);
                return;
            }

            // Confirm Delete
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
                        data: {
                            select: ids
                        },

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

    Bulkdelete('users', '.user-checkbox');


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

                    // Target table dynamically
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

    BulkUpdateStatus('users', '.user-checkbox');




    // Add User Form Open
    $(document).on('click', '#addUserBtn', function () {

        let url = $(this).data('url');
        let title = $(this).data('title');

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });

    // Add User Form Submit
    $(document).on('submit', '#addUserForm', function (e) {

        e.preventDefault();


        let formData = new FormData(this);
        console.log(formData);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();


                $('#addUserForm')[0].reset();
            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });

    // Delete Single User
    $(document).on('click', '#deleteUserBtn', function () {

        let url = $(this).data('url');

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "This user will be deleted permanently!",
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

                        $('#datatable').DataTable().ajax.reload();

                    },

                    error: function () {

                        toastr.error(
                            'Something went wrong.');
                    }
                });
            }
        });
    });

    // Edit User Form Open
    $(document).on('click', '#editUserBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');
        console.log(url);

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });

    // Edit User Form Submit
    $(document).on('submit', '#editUserForm', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });




    // Add Sport Form Open
    $(document).on('click', '#addSportBtn', function () {

        let url = $(this).data('url');
        let title = $(this).data('title');

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });
    // edit Sport Form Open
    $(document).on('click', '#editSportBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');
        // console.log(url);

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });

    // Add Sports Form Submit
    $(document).on('submit', '#addSportForm', function (e) {

        e.preventDefault();


        let formData = new FormData(this);
        console.log(formData);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();


                $('#addSportForm')[0].reset();
            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });
    // Edit Sport Form Submit
    $(document).on('submit', '#editSportForm', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });

    // Delete Single Sport
    $(document).on('click', '#deleteSportBtn', function () {

        let url = $(this).data('url');

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "This Sport will be deleted permanently!",
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

                        $('#datatable').DataTable().ajax.reload();

                    },

                    error: function () {

                        toastr.error(
                            'Something went wrong.');
                    }
                });
            }
        });
    });

    Bulkdelete('sports', '.user-checkbox');
    BulkUpdateStatus('sports', '.user-checkbox');


    // Add Level Form Open
    $(document).on('click', '#addLevelBtn', function () {

        let url = $(this).data('url');
        let title = $(this).data('title');
        // console.log(url, title);


        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });

    // Add Sports Form Submit
    $(document).on('submit', '#addLevelForm', function (e) {

        e.preventDefault();


        let formData = new FormData(this);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();


                $('#addLevelForm')[0].reset();
            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });

    // Delete Single Level
    $(document).on('click', '#deleteLevelBtn', function () {

        let url = $(this).data('url');

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "This Level will be deleted permanently!",
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

                        $('#datatable').DataTable().ajax.reload();

                    },

                    error: function () {

                        toastr.error(
                            'Something went wrong.');
                    }
                });
            }
        });
    });

    // edit Level Form Open
    $(document).on('click', '#editLevelBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');
        // console.log(url);

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });
    // Edit Level Form Submit
    $(document).on('submit', '#editLevelForm', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });

    Bulkdelete('levels', '.user-checkbox');
    BulkUpdateStatus('levels', '.user-checkbox');


    // Add Level Form Open
    $(document).on('click', '#addSportLevelBtn', function () {

        let url = $(this).data('url');
        let title = $(this).data('title');
        // console.log(url, title);


        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });


    // ADD LEVEL

    $(document).on('click', '#addNewLevelBtn', function (e) {

        e.preventDefault();

        let levelId = $('#levelDropdown').val();

        let levelName = $('#levelDropdown option:selected').text();

        let fees = $('#levelFees').val();

        // Dynamic Index
        let index = $('#levelTableBody tr').length;

        // Validation
        if (levelId == '') {

            toastr.error('Please select level');

            return;
        }

        if (fees == '') {

            toastr.error('Please enter fees');

            return;
        }

        // Prevent Duplicate Levels
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

        // Append Row
        let row = `

        <tr>

            <!-- Level -->
            <td>

                <div class="fw-semibold text-dark">

                    ${levelName}

                </div>

                <input type="hidden"
                       class="level-id-input"
                       name="levels[${index}][level_id]"
                       value="${levelId}">

            </td>

            <!-- Fees -->
            <td>

                <input type="number"
                       name="levels[${index}][fees]"
                       class="form-control"
                       value="${fees}"
                       placeholder="Enter fees">

            </td>

            <!-- Action -->
            <td class="text-center">

                <button type="button"
                        class="btn btn-danger btn-sm removeLevelBtn">

                    <i class="bi bi-trash"></i>

                </button>

            </td>

        </tr>

    `;

        $('#levelTableBody').append(row);

        // Reset Fields
        $('#levelDropdown').val('');

        $('#levelFees').val('');

    });


    // REMOVE LEVEL

    $(document).on('click', '.removeLevelBtn', function () {

        $(this).closest('tr').remove();

    });


    // ADD SPORTS LEVEL FORM SUBMIT

    $(document).on('submit', '#addSportsLevelsForm', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({

            url: $('#url').val(),

            method: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

                $('#addSportsLevelsForm')[0].reset();

                $('#levelTableBody').html('');
            },

            error: function (xhr) {

                $('.text-danger').text('');

                if (xhr.responseJSON.message) {

                    toastr.error(xhr.responseJSON.message);
                }

                let errors = xhr.responseJSON.errors;

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(value[0]);

                    });
                }
            }
        });

    });


    // LOAD EDIT FORM

    $(document).on('click', '#editSportsLevelsBtn', function () {

        let url = $(this).data('url');

        let title = $(this).data('title');

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({

            type: 'GET',

            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);

            }
        });

    });


    // EDIT SPORTS LEVEL FORM SUBMIT

    $(document).on('submit', '#editSportsLevelsForm', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({

            url: $('#url').val(),

            method: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

            },

            error: function (xhr) {

                $('.text-danger').text('');

                if (xhr.responseJSON.message) {

                    toastr.error(xhr.responseJSON.message);
                }

                let errors = xhr.responseJSON.errors;

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(value[0]);

                    });
                }
            }
        });

    });


    // ------Batches JS------

    $(document).on('change', '#sportDropdown', function () {

        let sportId = $(this).val();

        // Reset Levels
        $('#levelDropdown').html(
            '<option value="">Choose Level</option>'
        );

        // Check Sport Selected
        if (sportId != '') {

            $.ajax({

                url: '/get-sport-levels/' + sportId,

                method: 'GET',

                success: function (response) {

                    $.each(response, function (key, level) {

                        $('#levelDropdown').append(

                            `<option value="${level.id}">
                                ${level.name}
                            </option>`

                        );

                    });

                }

            });

        }

    });

    // Add Level Form Open
    $(document).on('click', '#addBatchBtn', function () {

        let url = $(this).data('url');
        let title = $(this).data('title');
        // console.log(url, title);


        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
                initSelect2();
                initTimePicker();
            }
        });
    });



    // Add Batches Form Submit

    // Submit form using button click
    $(document).on('click', '#saveBatchBtn', function (e) {

        e.preventDefault();

        $('#addBatchForm').trigger('submit');

    });

    $(document).on('submit', '#addBatchForm', function (e) {

        e.preventDefault();

        // Capacity Validation
        let capacity = parseInt($('input[name="capacity"]').val());

        let selectedPlayers = $('select[name="players[]"]').val();

        selectedPlayers = selectedPlayers ? selectedPlayers.length : 0;

        if (selectedPlayers > capacity) {

            toastr.error(`You can select maximum ${capacity} players only.`);

            return;
        }

        let formData = new FormData(this);

        $.ajax({

            url: $('#url').val(),

            type: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

                $('#addBatchForm')[0].reset();

                $('.select2').val(null).trigger('change');

            },

            error: function (xhr) {

                $('.text-danger').text('');

                let errors = xhr.responseJSON.errors;

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(value[0]);

                    });

                }

            }

        });

    });
    // edit Batch Form Open
    $(document).on('click', '#editBatchBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');
        // console.log(url);

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
                initSelect2();
                initTimePicker();
            }
        });
    });

    // Update Batch Button
    $(document).on('click', '#updateBatchBtn', function (e) {

        e.preventDefault();

        $('#editBatchForm').trigger('submit');

    });

    // Edit Batch Form Submit
    $(document).on('submit', '#editBatchForm', function (e) {

        e.preventDefault();

        let capacity = parseInt($('input[name="capacity"]').val());

        let selectedPlayers = $('select[name="players[]"]').val();

        selectedPlayers = selectedPlayers ? selectedPlayers.length : 0;

        if (selectedPlayers > capacity) {

            toastr.error(`You can select maximum ${capacity} players only.`);

            return;
        }

        let formData = new FormData(this);

        $.ajax({

            url: $('#url').val(),

            type: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

            },

            error: function (xhr) {

                $('.text-danger').text('');

                let errors = xhr.responseJSON.errors;

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(value[0]);

                    });

                }

            }

        });

    });


    // Delete Single Batch
    $(document).on('click', '#deleteBatchBtn', function () {

        let url = $(this).data('url');

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "This Batch will be deleted permanently!",
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

                        $('#datatable').DataTable().ajax.reload();

                    },

                    error: function () {

                        toastr.error(
                            'Something went wrong.');
                    }
                });
            }
        });
    });


    Bulkdelete('batches', '.user-checkbox');
    BulkUpdateStatus('batches', '.user-checkbox');

    // Add fees Form Open
    $(document).on('click', '#addFeesGenerateBtn', function () {

        let url = $(this).data('url');
        let title = $(this).data('title');

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });

    // Add fees Form Submit
    $(document).on('submit', '#addFeesGenerateForm', function (e) {

        e.preventDefault();


        let formData = new FormData(this);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();


                $('#addFeesGenerateForm')[0].reset();
            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });


    // Add Player Fee Form Open
    $(document).on('click', '#addPlayerFeeBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {
                $('#offCanvasContent').html(response);
            }
        });
    });


    // edit Player Fee Form Open
    $(document).on('click', '#editPlayerFeeBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');
        console.log(title);

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,

            success: function (response) {

                $('#offCanvasContent').html(response);
            }
        });
    });
    // Edit Player Fee Form Submit

    $(document).on('submit', '#editPlayerFeeForm', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({

            url: $('#url').val(),

            method: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#datatable').DataTable().ajax.reload();

            },

            error: function (xhr) {

                $('.text-danger').text('');

                if (xhr.responseJSON.message) {

                    toastr.error(xhr.responseJSON.message);
                }

                let errors = xhr.responseJSON.errors;

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(value[0]);

                    });
                }
            }
        });

    });

    /*
    |--------------------------------------------------------------------------
    | Players Management Form Handlers
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Players Management Form Handlers
    |--------------------------------------------------------------------------
    */

    let assignmentIndex = 1;

    // Add Assignment Row
    $(document).on('click', '#add_assignment_btn', function () {
        let container = $('#assignments_container');
        let index = assignmentIndex++;
        let sportsOptions = $('#sport_options_helper').html();

        let rowHtml = `
        <div class="card border border-secondary-subtle rounded-4 mb-3 p-3 assignment-row bg-white animate__animated animate__fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold text-secondary small">Assignment #<span class="row-num">${index + 1}</span></span>
                <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-assignment-btn">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-dark mb-1">Sport</label>
                    <select class="form-select form-select-sm sport-select" name="assignments[${index}][sport_id]" required>
                        <option value="" disabled selected>Select sport</option>
                        ${sportsOptions}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-dark mb-1">Level</label>
                    <select class="form-select form-select-sm level-select" name="assignments[${index}][level_id]" required disabled>
                        <option value="" disabled selected>Select sport first</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-dark mb-1">Batch</label>
                    <select class="form-select form-select-sm batch-select" name="assignments[${index}][batch_id]" required disabled>
                        <option value="" disabled selected>Select level first</option>
                    </select>
                </div>
            </div>
        </div>`;

        container.append(rowHtml);
        updateRemoveButtons();
    });

    // Remove Assignment Row
    $(document).on('click', '.remove-assignment-btn', function () {
        $(this).closest('.assignment-row').remove();
        reindexRows();
        updateRemoveButtons();
    });

    function reindexRows() {
        assignmentIndex = 0;
        $('#assignments_container .assignment-row').each(function (idx) {
            let row = $(this);
            row.find('.row-num').text(idx + 1);
            row.find('.sport-select').attr('name', `assignments[${idx}][sport_id]`);
            row.find('.level-select').attr('name', `assignments[${idx}][level_id]`);
            row.find('.batch-select').attr('name', `assignments[${idx}][batch_id]`);
            assignmentIndex = idx + 1;
        });
    }

    function updateRemoveButtons() {
        let rows = $('#assignments_container .assignment-row');
        if (rows.length <= 1) {
            rows.find('.remove-assignment-btn').addClass('d-none');
        } else {
            rows.find('.remove-assignment-btn').removeClass('d-none');
        }
    }

    // Cascade sport levels in dynamic rows
    $(document).on('change', '.sport-select', function () {
        let select = $(this);
        let sportId = select.val();
        let row = select.closest('.assignment-row');
        let levelSelect = row.find('.level-select');
        let batchSelect = row.find('.batch-select');

        levelSelect.html('<option value="" disabled selected>Loading...</option>').prop('disabled', true);
        batchSelect.html('<option value="" disabled selected>Select level first</option>').prop('disabled', true);

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
            },
            error: function () {
                levelSelect.html('<option value="" disabled selected>Error loading levels</option>');
            }
        });
    });

    // Cascade level batches in dynamic rows
    $(document).on('change', '.level-select', function () {
        let select = $(this);
        let levelId = select.val();
        let row = select.closest('.assignment-row');
        let sportId = row.find('.sport-select').val();
        let batchSelect = row.find('.batch-select');

        batchSelect.html('<option value="" disabled selected>Loading...</option>').prop('disabled', true);

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
            },
            error: function () {
                batchSelect.html('<option value="" disabled selected>Error loading batches</option>');
            }
        });
    });

    // Add Player Form Open
    $(document).on('click', '#addPlayerBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,
            success: function (response) {
                $('#offCanvasContent').html(response);
                assignmentIndex = 1; // Reset rows index
            }
        });
    });

    // Add Player Form Submit
    $(document).on('submit', '#addPlayerForm', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(response.message);
                $('#offcanvasScrolling').offcanvas('hide');
                $('#datatable').DataTable().ajax.reload();
                $('#addPlayerForm')[0].reset();
            },
            error: function (xhr) {
                $('.text-danger').text('');
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function (key, value) {
                        let errorId = key.replace(/\./g, '_') + 'Error';
                        $('#' + errorId).text(value[0]);
                        $('#' + key + 'Error').text(value[0]);
                    });
                } else if (xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                }
            }
        });
    });

    // Edit Player Form Open
    $(document).on('click', '#editPlayerBtn', function () {
        let url = $(this).data('url');
        let title = $(this).data('title');

        $('#offcanvasScrollingLabel').text(title);

        $.ajax({
            type: 'GET',
            url: url,
            success: function (response) {
                $('#offCanvasContent').html(response);
                // Set the initial index based on loaded rows
                assignmentIndex = $('#assignments_container .assignment-row').length;
            }
        });
    });

    // Edit Player Form Submit
    $(document).on('submit', '#editPlayerForm', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(response.message);
                $('#offcanvasScrolling').offcanvas('hide');
                $('#datatable').DataTable().ajax.reload();
            },
            error: function (xhr) {
                $('.text-danger').text('');
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function (key, value) {
                        let errorId = key.replace(/\./g, '_') + 'Error';
                        $('#' + errorId).text(value[0]);
                        $('#' + key + 'Error').text(value[0]);
                    });
                } else if (xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                }
            }
        });
    });

    // Delete Single Player
    $(document).on('click', '#deletePlayerBtn', function () {
        let url = $(this).data('url');

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "This player will be deleted permanently!",
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
                        $('#datatable').DataTable().ajax.reload();
                    },
                    error: function () {
                        toastr.error('Something went wrong.');
                    }
                });
            }
        });
    });

    Bulkdelete('players', '.user-checkbox');
    BulkUpdateStatus('players', '.user-checkbox');



    // Function to toggle inputs status and styling in Penalty card
    function togglePenaltyInputs() {
        const isChecked = $('#allow_penalty').is(':checked');
        const $section = $('#penaltyFieldsSection');

        if (isChecked) {
            $section.removeClass('disabled-section');
            $section.find('input, select').prop('disabled', false);
        } else {
            $section.addClass('disabled-section');
            $section.find('input, select').prop('disabled', true);
            // Clear error messages if disabled
            $section.find('.text-danger').text('');
        }
    }

    // Bind change event to Allow Penalty Switch
    $('#allow_penalty').on('change', function () {
        togglePenaltyInputs();
    });

    // Update Input group icon based on Penalty type selection
    $('#penalty_type').on('change', function () {
        const val = $(this).val();
        const $icon = $('#penaltyAmountIcon');
        if (val === 'percentage') {
            $icon.text('%');
        } else {
            $icon.text('₹');
        }
    });

    // Penalty Settings Form Submission
    $('#penaltySettingsForm').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $form.find('.btn-submit');
        const $spinner = $btn.find('.spinner-border');
        const url = $('#penalty_url').val();

        // Show loading state
        $btn.prop('disabled', true);
        $spinner.removeClass('d-none');
        $form.find('.text-danger').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serialize(),
            success: function (response) {
                toastr.success(response.message);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        $('#' + key + 'Error').text(value[0]);
                    });
                } else {
                    toastr.error('An unexpected error occurred. Please try again.');
                }
            },
            complete: function () {
                // Hide loading state
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
            }
        });
    });

    // Discount Settings Form Submission
    $('#discountSettingsForm').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $form.find('.btn-submit');
        const $spinner = $btn.find('.spinner-border');
        const url = $('#discount_url').val();

        // Show loading state
        $btn.prop('disabled', true);
        $spinner.removeClass('d-none');
        $form.find('.text-danger').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serialize(),
            success: function (response) {
                toastr.success(response.message);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        $('#' + key + 'Error').text(value[0]);
                    });
                } else {
                    toastr.error('An unexpected error occurred. Please try again.');
                }
            },
            complete: function () {
                // Hide loading state
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
            }
        });
    });

    // Update labels, symbols and validation rules based on Discount type selection
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

    $('#discount_type').on('change', function () {
        updateDiscountTypeUI();
    });

    if ($('#discount_type').length > 0) {
        updateDiscountTypeUI();
    }

});
