        <!-- Script JS (your custom scripts) -->
    <script src="http://localhost/PM/password-manager-app/assets/script.js"></script>
    <link rel="stylesheet" href="http://localhost/PM/password-manager-app/assets/style.css">

    <!-- jQuery (full version, required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" 
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoFhbGU+6BZp6G7niu735Sk7lN" 
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" 
            integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" 
            crossorigin="anonymous"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Init -->
    <script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#accountsTable')) {
        $('#accountsTable').DataTable({
            pageLength: 10,       // default 10 rows
            lengthChange: true,   // "Show entries" dropdown
            searching: true,      // Search box enabled
            ordering: true,       // Enable column sorting
            info: true,           // Show "Showing 1 to 10 of X entries"
            paging: true,         // Enable pagination
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' + // Top: left=length, right=filter
                 'rt' +                                  // Table
                 '<"row"<"col-sm-5"i><"col-sm-7"p>>'    // Bottom: left=info, right=pagination
        });
    }
});

</script>


</body>
</html>
