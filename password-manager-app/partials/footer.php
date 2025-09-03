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
<!-- DataTables CSS with Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS with Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

$(document).ready(function() {
    $('#accountsTable').DataTable({
        "order": [[0, "desc"]],
        paging: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50,100],
        ordering: true,
        searching: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search accounts...",
           
        }
    });
});
</script>
<script>





</body>
</html>
