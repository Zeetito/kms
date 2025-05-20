
// Datatable
$(document).ready(function() {
     updateCheckIcons();
 $('#usersTable').DataTable({
        responsive: true,
        paging: true,             // Enable pagination
        pageLength: 10,           // Default items per page
        lengthMenu: [10, 25, 50, 100], // Options for items per page
        ordering: true,           // Enable column sorting
        order: [[0, 'asc']],      // Default ordering (by ID column)
        searching: true,          // Enable search/filter
        info: true,               // Shows table information (Showing x of y entries)
        language: {
            searchPlaceholder: "Search users...",
            search: "",
        },
        columnDefs: [
            { orderable: false, targets: -1 } // Disables ordering on Actions column
        ],
    });

    // DataTable end

    // Edit User Modal Handler
    $('.edit-user-btn').click(function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        const userEmail = $(this).data('email');
        
        $('#editUserForm').attr('action', `/users/${userId}`);
        $('#edit-name').val(userName);
        $('#edit-email').val(userEmail);
    });

    // Delete User Modal Handler
    $('.delete-user-btn').click(function() {
        const userId = $(this).data('id');
        $('#deleteUserForm').attr('action', `/users/${userId}`);
    });

    // Check Attendance Modal Handler
    $('.check-attendance-btn').click(function () {
        const userId = $(this).data('id');
        const userName = $(this).data('name');

        // Set form action
        const actionUrl = `{{ url('/check-zone-user') }}/${userId}`;
        $('#checkAttendanceForm').attr('action', actionUrl);

        // Set user's name in modal
        $('#attendance-user-name').text(userName);

        // Store user ID in form data for JS reference
        $('#checkAttendanceForm').data('user-id', userId);
    });


    $('#markAttendanceBtn').click(function () {
        const userId = $('#checkAttendanceForm').data('user-id');
        let cached = JSON.parse(localStorage.getItem('marked_users_id')) || {};
        let ids = cached.data || [];
        const now = Date.now();

        // Reset expired cache
        if (!cached.expiresAt || now > cached.expiresAt) {
            ids = [];
        }

        if (!ids.includes(userId)) {
            ids.push(userId);
        }

        localStorage.setItem('marked_users_id', JSON.stringify({
            data: ids,
            expiresAt: now + 12 * 60 * 60 * 1000 // 12 hours
        }));

        // ✅ Update icons
        updateCheckIcons();

        // ✅ Close modal
        $('#checkAttendanceModal').modal('hide');
    });



    function updateCheckIcons() {
        const cache = JSON.parse(localStorage.getItem('marked_users_id'));
        if (!cache || Date.now() > cache.expiresAt) {
            localStorage.removeItem('marked_users_id'); // Clear expired
            return;
        }

        const markedUsers = cache.data;

        $('.check-attendance-btn').each(function () {
            const userId = $(this).data('id');
            const icon = $(this).find('i');

            if (markedUsers.includes(userId)) {
                icon.addClass('text-success'); // green color
            } else {
                icon.removeClass('text-success');
            }
        });
    }

    $(document).ready(function () {
        updateCheckIcons();
    });

    $('#submitAttendanceModal').on('show.bs.modal', function () {
        const cache = JSON.parse(localStorage.getItem('marked_users_id'));

        if (!cache || Date.now() > cache.expiresAt || !Array.isArray(cache.data)) {
            // Clear input if no valid cache
            $('#markedUserIdsInput').val('');
            return;
        }

        const ids = cache.data;
        $('#markedUserIdsInput').val(JSON.stringify(ids)); // Submit as JSON string
    });


});
