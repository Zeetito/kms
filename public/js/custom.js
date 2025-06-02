$(document).ready(function() {

    // Prevent modal from closing on select2 dropdown click
    $(document).on('select2:opening select2:closing', function (e) {
        $(e.target).parentsUntil('.modal').css('z-index', 999999);
        e.stopPropagation();
    });

    $('form').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault(); // Prevent accidental form submit
        }
    });


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
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
    });

    $('#TablePrint').DataTable({
        responsive: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: ['excelHtml5', 'pdfHtml5', 'print'],
    });

    // Single Edit User Modal Handler
    $('.edit-user-btn').click(function() {


        const userId = $(this).data('id');
        $('#editUserForm').attr('action', `/edit-zone-user/${userId}`);
        $('#edit-firstname').val($(this).data('firstname'));
        $('#edit-lastname').val($(this).data('lastname'));
        $('#edit-gender').val($(this).data('gender'));
        $('#edit-is_baptised').val($(this).data('baptised'));
        $('#edit-active_contact').val($(this).data('contact'));
        $('#edit-email').val($(this).data('email'));
        $('#edit-residence').val($(this).data('residence'));

        // 🆕 Handle Custom Residence
        const isCustom = $(this).data('is_custom_residence') == 1;
        $('#edit-is_custom_residence').prop('checked', isCustom).trigger('change'); // triggers show/hide
        $('#edit-custom_residence_name').val($(this).data('custom_residence_name'));
        $('#edit-custom_residence_description').val($(this).data('custom_residence_description'));

            // ✅ Program and Year (if using them)
            $('#edit-program').val($(this).data('program')).trigger('change.select2');
            $('#edit-year').val($(this).data('year'));
            $('#edit-status').val($(this).data('status')).trigger('change');
            $('#edit-occupation').val($(this).data('occupation'));
            $('#edit-occupation_description').val($(this).data('occupation_description'));
    });


        $('#edit-program').select2({
            theme: 'bootstrap-5',
            placeholder: "Select a program",
            allowClear: true
        });

    // Changing the Status of a Student
    $('#edit-status').change(function () {
        const value = $(this).val();

        // Hide all conditional fields first
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





    // Delete User Modal Handler
    $('.delete-user-btn').click(function() {
        const userId = $(this).data('id');
        $('#deleteUserForm').attr('action', `/users/${userId}`);
    });

    // Check Attendance Modal Handler
    $('.check-attendance-btn').click(function () {
        const userId = $(this).data('id');
        $('#checkAttendanceForm').attr('action', `/check-zone-user/${userId}`);
        $('#attendance-user-name').text($(this).data('name'));
        $('#checkAttendanceForm').data('user-id', userId);
    });

    // Mark Attendance
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

    // Custom Residence handlers (Add & Edit)
    $('#is_custom_residence, #edit-is_custom_residence').change(function() {
        let prefix = $(this).attr('id') === 'edit-is_custom_residence' ? 'edit-' : '';
        if($(this).is(':checked')) {
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
