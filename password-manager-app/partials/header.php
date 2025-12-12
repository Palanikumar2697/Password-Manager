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
var table;



// ⭐ Initialize DataTable
$(document).ready(function () {

    table = $("#accountsTable").DataTable({
        order: [[1, "desc"]],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search accounts...",
            emptyTable: "No accounts found"
        }
    });

    // ⭐ AUTO-FILL DROPDOWNS FROM TABLE DATA
    let userSet = new Set();
    let creatorSet = new Set();

    $("#accountsTable tbody tr").each(function () {
        userSet.add($(this).find("td").eq(3).text().trim());  // username col
        creatorSet.add($(this).find("td").eq(7).text().trim()); // created by col
    });

    userSet.forEach(u => {
        $("#fUserName").append(`<option value="${u}">${u}</option>`);
    });

    creatorSet.forEach(c => {
        $("#fCreateBy").append(`<option value="${c}">${c}</option>`);
    });


    // ⭐ CUSTOM DATATABLE FILTER LOGIC
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {

        let dateFrom = $("#fDateFrom").val();
        let dateTo = $("#fDateTo").val();
        let accName = $("#fAccountName").val().toLowerCase();
        let username = $("#fUserName").val();
        let createdBy = $("#fCreateBy").val();

        // TABLE COLUMNS (adjust if needed)
        let rowDate = data[1];      // created_at column
        let rowAcc = data[2].toLowerCase();
        let rowUser = data[3];
        let rowCreator = data[7];

        // DATE FILTER
        if (dateFrom !== "" && new Date(rowDate) < new Date(dateFrom)) return false;
        if (dateTo !== "" && new Date(rowDate) > new Date(dateTo)) return false;

        // ACCOUNT NAME FILTER
        if (accName && !rowAcc.includes(accName)) return false;

        // USERNAME FILTER
        if (username && rowUser !== username) return false;

        // CREATED BY FILTER
        if (createdBy && rowCreator !== createdBy) return false;

        return true;
    });


    // ⭐ APPLY BUTTON
    $("#applyFilters").on("click", function () {
        table.draw();
    });

    // ⭐ RESET BUTTON
    $("#resetFilters").on("click", function () {

        $("#fDateFrom").val("");
        $("#fDateTo").val("");
        $("#fAccountName").val("");
        $("#fUserName").val("");
        $("#fCreateBy").val("");

        table.search("").columns().search("");

        table.draw();
    });

});



</script>
