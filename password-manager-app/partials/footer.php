<!-- Your custom CSS -->
<link rel="stylesheet" href="http://localhost/PM/password-manager-app/assets/style.css">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- DataTables CSS with Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- jQuery (needed for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap 5 Bundle JS (includes Popper.js automatically) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS with Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Your custom script -->
<script src="http://localhost/PM/password-manager-app/assets/script.js"></script>

<script>
$(document).ready(function () {
    // ✅ Initialize DataTable only once
    if (!$.fn.DataTable.isDataTable('#accountsTable')) {
        $('#accountsTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50],
            "ordering": true,
            "searching": true
        });
    }

    // ✅ Password toggle (works on dynamically loaded rows too)
    $(document).on("click", ".toggle-password", function () {
        const input = $(this).closest(".input-group").find("input");
        const icon = $(this).find("i");

        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
            input.attr("type", "password");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        }
    });
});
</script>
