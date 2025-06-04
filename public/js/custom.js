$(document).ready(function () {
    updateCheckIcons();

    // Initialize DataTables
    $('#usersTable').DataTable({
        responsive: true,
        paging: false,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        order: [[0, 'asc']],
        searching: true,
        info: true,
        language: {
            searchPlaceholder: "Search users...",
            search: "",
        },
        columnDefs: [{ orderable: false, targets: -1 }],
    });

    $('#TablePrint').DataTable({
        responsive: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: ['excelHtml5', 'pdfHtml5', 'print'],
    });

    // Edit User Modal
    $('.edit-user-btn').click(function () {
        const userId = $(this).data('id');
        $('#editUserForm').attr('action', `/edit-zone-user/${userId}`);
        $('#edit-user-id').val(userId);
        $('#edit-firstname').val($(this).data('firstname'));
        $('#edit-lastname').val($(this).data('lastname'));
        $('#edit-gender').val($(this).data('gender'));
        $('#edit-is_baptised').val($(this).data('baptised'));
        $('#edit-active_contact').val($(this).data('contact'));
        $('#edit-email').val($(this).data('email'));
        $('#edit-residence').val($(this).data('residence'));
        $('#edit-room').val($(this).data('room'));

        const isCustom = $(this).data('is_custom_residence') == 1;
        $('#edit-is_custom_residence').prop('checked', isCustom).trigger('change');
        $('#edit-custom_residence_name').val($(this).data('custom_residence_name'));
        $('#edit-custom_residence_description').val($(this).data('custom_residence_description'));

        const status = $(this).data('status');
        if (status) {
            $('#edit-status').val(status.toLowerCase()).trigger('change');
        } else {
            $('#edit-status').val('').trigger('change');
        }

        $('#edit-year').val($(this).data('year'));
        $('#edit-program').val($(this).data('program')).trigger('change.select2');
        $('#edit-occupation').val($(this).data('occupation'));
        $('#edit-occupation_description').val($(this).data('occupation_description'));
    });

    // Reset form on modal close
    $('#editUserModal').on('hidden.bs.modal', function () {
        $('#editUserForm')[0].reset();
        $('#edit-is_custom_residence').prop('checked', false).trigger('change');
    });

    // Program dropdown
    $('#edit-program').select2({
        theme: 'bootstrap-5',
        placeholder: "Select a program",
        allowClear: true
    });

    // Toggle student/other fields
    $('#edit-status').change(function () {
        const value = $(this).val();
        $('#student-fields, #other-fields').hide();
        $('#edit-year, #edit-program, #edit-occupation, #edit-occupation_description').prop('required', false);

        if (value === 'student') {
            $('#student-fields').show();
            $('#edit-year, #edit-program').prop('required', true);
        } else if (value === 'other') {
            $('#other-fields').show();
            $('#edit-occupation, #edit-occupation_description').prop('required', true);
        }
    });

    // Toggle custom residence
    $('#edit-is_custom_residence, #is_custom_residence').change(function () {
        let prefix = $(this).attr('id') === 'edit-is_custom_residence' ? 'edit-' : '';
        if ($(this).is(':checked')) {
            $(`#${prefix}main_residence`).hide();
            $(`#${prefix}custom_residence_details`).show();
            $(`#${prefix}custom_residence_name, #${prefix}custom_residence_description`).prop('required', true);
            $(`#${prefix}residence`).prop('required', false);
        } else {
            $(`#${prefix}main_residence`).show();
            $(`#${prefix}custom_residence_details`).hide();
            $(`#${prefix}custom_residence_name, #${prefix}custom_residence_description`).prop('required', false);
            $(`#${prefix}residence`).prop('required', true);
        }
    });

    // Delete user
    $('.delete-user-btn').on('click', function () {
        const userId = $(this).data('id');
        $('#deleteUserForm').attr('action', `/users/${userId}`);
        const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        modal.show();
    });

    // Attendance check
    $('.check-attendance-btn').click(function () {
        const userId = $(this).data('id');
        $('#checkAttendanceForm').attr('action', `/check-zone-user/${userId}`);
        $('#attendance-user-name').text($(this).data('name'));
        $('#checkAttendanceForm').data('user-id', userId);
    });

    $('#markAttendanceBtn').click(function () {
        const userId = $('#checkAttendanceForm').data('user-id');
        let cached = JSON.parse(localStorage.getItem('marked_users_id')) || {};
        let ids = cached.data || [];
        const now = Date.now();

        if (!cached.expiresAt || now > cached.expiresAt) {
            ids = [];
        }

        if (!ids.includes(userId)) {
            ids.push(userId);
        }

        localStorage.setItem('marked_users_id', JSON.stringify({
            data: ids,
            expiresAt: now + 12 * 60 * 60 * 1000
        }));

        updateCheckIcons();
        $('#checkAttendanceModal').modal('hide');
    });

    $('#submitAttendanceModal').on('show.bs.modal', function () {
        const cache = JSON.parse(localStorage.getItem('marked_users_id'));
        if (!cache || Date.now() > cache.expiresAt || !Array.isArray(cache.data)) {
            $('#markedUserIdsInput').val('');
            return;
        }
        $('#markedUserIdsInput').val(JSON.stringify(cache.data));
    });
});

function updateCheckIcons() {
    const cache = JSON.parse(localStorage.getItem('marked_users_id'));
    if (!cache || Date.now() > cache.expiresAt) {
        localStorage.removeItem('marked_users_id');
        return;
    }

    const markedUsers = cache.data;

    $('.check-attendance-btn').each(function () {
        const userId = $(this).data('id');
        const icon = $(this).find('i');

        if (markedUsers.includes(userId)) {
            icon.addClass('text-success');
        } else {
            icon.removeClass('text-success');
        }
    });
}
