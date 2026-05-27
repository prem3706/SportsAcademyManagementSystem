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

        const passwordField = $('#password', '#password_confirmation');
        const icon = $('#toggleIcon');

        // Check current type attribute
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';

        // Toggle input type and icon classes
        passwordField.attr('type', type);
        icon.toggleClass('bi-eye-slash bi-eye');
    });

    // DataTable Filters
    $('#users-table').on('preXhr.dt', function (e, settings, data) {

        data.status = $('#statusFilter').val();
        data.role = $('#roleFilter').val();
    });

    // Status Filter
    $(document).on('change', '#statusFilter', function () {

        $('#users-table').DataTable().ajax.reload();
    });

    // Role Filter
    $(document).on('change', '#roleFilter', function () {

        $('#users-table').DataTable().ajax.reload();
    });

    // Refresh Button


    $(document).on('click', '#refreshTableBtn', function () {

        $('#statusFilter').val('');
        $('#roleFilter').val('');

        $('#users-table').DataTable().ajax.reload();

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
    $('#bulkDeleteBtn').on('click', function () {

        let ids = [];

        $('.user-checkbox:checked').each(function () {

            ids.push($(this).val());
        });

        // No Selection
        if (ids.length === 0) {

            toastr.warning('Please select at least one user.');

            return;
        }

        // Confirm Delete
        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "Selected users will be deleted permanently!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete!",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "/users/bulk-delete",
                    type: "DELETE",
                    data: {
                        select: ids
                    },

                    success: function (response) {

                        toastr.success(response.message);

                        $('#users-table').DataTable().ajax.reload();

                        $('#select-all').prop('checked', false);

                        $('#bulkActionBar').addClass(
                            'd-none');
                    },

                    error: function (xhr) {

                        toastr.error(xhr.responseJSON
                            .message);
                    }
                });
            }
        });
    });

    // Bulk Status Update
    $('#bulkUpdateBtn').on('click', function () {
        let ids = [];
        let status = $('#statusUpdate').val();

        $('.user-checkbox:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            toastr.warning('Please select at least one user.');
            return;
        }

        if (status === '') {
            toastr.warning('Please select status.');
            return;
        }

        $.ajax({
            url: "/users/bulk-update",
            type: "POST",
            data: {
                _method: 'PATCH',
                select: ids,
                status: status
            },
            success: function (response) {
                toastr.success(response.message);
                $('#users-table').DataTable().ajax.reload();
                $('#select-all').prop('checked', false);
                $('.user-checkbox').prop('checked', false);
                $('#bulkActionBar').addClass('d-none');
            },
            error: function (xhr) {
                $.each(errors, function (key, value) {

                    toastr.key(value[0]);
                });
            }
        });
    });

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

        $.ajax({
            url: $('#url').val(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                toastr.success(response.message);

                $('#offcanvasScrolling').offcanvas('hide');

                $('#users-table').DataTable().ajax.reload();

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
    $(document).on('click', '.deleteUserBtn', function () {

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

                        $('#users-table').DataTable().ajax
                            .reload();
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

                $('#users-table').DataTable().ajax.reload();
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

});
