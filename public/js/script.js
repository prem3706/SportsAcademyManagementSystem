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

        data.status = $('#statusFilter').val();
        data.role = $('#roleFilter').val();
    });
    // // Sports DataTable Filters
    // $('#sports-table').on('preXhr.dt', function (e, settings, data) {

    //     data.status = $('#statusFilter').val();
    // });

    // Status Filter
    $(document).on('change', '#statusFilter', function () {

        $('#datatable').DataTable().ajax.reload();


    });

    // Role Filter
    $(document).on('change', '#roleFilter', function () {

        $('#datatable').DataTable().ajax.reload();


    });

    // Refresh Button


    $(document).on('click', '#refreshTableBtn', function () {

        $('#statusFilter').val('');
        $('#roleFilter').val('');

        $('#datatable').DataTable().ajax.reload();



        $(this).addClass('d-none');

    });

    $(document).on('change', '#statusFilter, #roleFilter', function () {

        let status = $('#statusFilter').val();
        let role = $('#roleFilter').val();

        if (status !== '' || role !== '') {

            $('#refreshTableBtn').removeClass('d-none');

        } else {

            $('#refreshTableBtn').addClass('d-none');
        }
    });

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


    let index = $('#levelTableBody tr').length;

    // Add Level
    $(document).on('click', '#addNewLevelBtn', function (e) {

        e.preventDefault();

        let levelId = $('#levelDropdown').val();

        let levelName = $('#levelDropdown option:selected').text();

        let fees = $('#levelFees').val();

        // Validation
        if (levelId == '') {

            toastr.error('Please select level');

            return;
        }

        if (fees == '') {

            toastr.error('Please enter fees');

            return;
        }

        // Prevent duplicate levels
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

        <tr id="row_${index}">

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
                        class="btn btn-danger btn-sm removeLevelBtn"
                        data-row="${index}">

                    <i class="bi bi-trash"></i>

                </button>

            </td>

        </tr>

    `;

        $('#levelTableBody').append(row);

        index++;

        // Reset Fields
        $('#levelDropdown').val('');

        $('#levelFees').val('');

    });

    // Remove Level
    $(document).on('click', '.removeLevelBtn', function () {

        let rowId = $(this).data('row');

        $('#row_' + rowId).remove();

    });

    // Add Sports Form Submit
    $(document).on('submit', '#addSportsLevelsForm', function (e) {

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


                $('#addLevelForm')[0].reset();
            },

            error: function (xhr, message) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');
                if (xhr.responseJSON.message) {

                    toastr.error(xhr.responseJSON.message);

                }

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });

    $(document).on('click', '#editSportsLevelsBtn', function () {
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

    $(document).on('submit', '#editSportsLevelsForm', function (e) {

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


                $('#editSportsLevelsForm')[0].reset();
            },

            error: function (xhr, message) {

                let errors = xhr.responseJSON.errors;

                $('.text-danger').text('');
                if (xhr.responseJSON.message) {

                    toastr.error(xhr.responseJSON.message);

                }

                if (errors) {

                    $.each(errors, function (key, value) {

                        $('#' + key + 'Error').text(
                            value[0]);
                    });
                }
            }
        });
    });




});
