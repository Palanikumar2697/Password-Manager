<!-- jQuery (for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables with Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap 5 JS bundle (required for dropdowns, modals, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Your custom JS -->
<script src="http://localhost/PM/password-manager-app/assets/script.js"></script>
<script>
$(document).ready(function () {

    if (!$.fn.DataTable.isDataTable('#accountsTable')) {
        $('#accountsTable').DataTable({
            destroy: true,
            order: [[0, "desc"]],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search accounts...",
                emptyTable: "No accounts found"
            }
        });
    }

});
</script>
