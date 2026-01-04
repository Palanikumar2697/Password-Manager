<!-- jQuery (for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables with Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<!-- Bootstrap 5 JS bundle (required for dropdowns, modals, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Your custom JS -->
<script src="http://localhost/PM/password-manager-app/assets/script.js"></script>
<script>
let table; // ✅ GLOBAL

$(document).ready(function () {

    // -----------------------------
    // DataTable INIT
    // -----------------------------
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

    populateFilters();

    // -----------------------------
    // CUSTOM FILTER LOGIC
    // -----------------------------
    $.fn.dataTable.ext.search.push(function (settings, data) {

        const fromDate  = $("#fDateFrom").val();
        const toDate    = $("#fDateTo").val();
        const accName   = $("#fAccountName").val().toLowerCase();
        const username  = $("#fUserName").val().toLowerCase();
        const createdBy = $("#fCreateBy").val().toLowerCase();

        // Column indexes
        // 1 = Date | 2 = Account Name | 3 = Username | 7 = Created By
        let rowDate = null;

        if (data[1]) {
            const parts = data[1].split("-");
            rowDate = new Date(parts[2], parts[1] - 1, parts[0]); // dd-mm-yyyy
        }

        if (fromDate && rowDate < new Date(fromDate)) return false;
        if (toDate && rowDate > new Date(toDate)) return false;

        if (accName && !data[2].toLowerCase().includes(accName)) return false;
        if (username && !data[3].toLowerCase().includes(username)) return false;
        if (createdBy && !data[7].toLowerCase().includes(createdBy)) return false;

        return true;
    });

    // -----------------------------
    // APPLY FILTER
    // -----------------------------
    $("#applyFilters").on("click", function () {
        table.draw();
        $("#filterPanel").hide();
    });

    // -----------------------------
    // RESET FILTER
    // -----------------------------
    $("#resetFilters").on("click", function () {
        ["fDateFrom","fDateTo","fAccountName","fUserName","fCreateBy"]
            .forEach(id => $("#" + id).val(""));

        table.draw();
    });

    // -----------------------------
    // EXPORT FILTERED ROWS
    // -----------------------------
    $("#exportBtn").on("click", function () {

        var filteredData = table.rows({ search: 'applied' }).nodes();

        if (filteredData.length === 0) {
            Swal.fire('No data', 'There are no rows to export.', 'warning');
            return;
        }

        var passwordColIndex = -1;
        $("#accountsTable thead th").each(function (index) {
            if ($(this).text().trim().toLowerCase() === "password") {
                passwordColIndex = index;
            }
        });

        var exportTable = document.createElement('table');

        // Headers
        var headerClone = $("#accountsTable thead th").clone().filter(function () {
            return $(this).text().trim().toLowerCase() !== "action";
        });

        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');
        headerClone.each(function () {
            headerRow.appendChild(this);
        });
        thead.appendChild(headerRow);
        exportTable.appendChild(thead);

        // Body
        var tbody = document.createElement('tbody');

        $(filteredData).each(function () {
            var cells = this.querySelectorAll('td');
            var newRow = document.createElement('tr');

            cells.forEach(function (cell, i) {
                var headerText = $("#accountsTable thead th").eq(i).text().trim().toLowerCase();
                if (headerText === "action") return;

                var newCell = cell.cloneNode(true);
                if (i === passwordColIndex) newCell.innerText = '****';

                newRow.appendChild(newCell);
            });

            tbody.appendChild(newRow);
        });

        exportTable.appendChild(tbody);

        var workbook = XLSX.utils.table_to_book(exportTable, { sheet: "Accounts" });
        XLSX.writeFile(workbook, 'Accounts_Data.xlsx');
    });

});

// -----------------------------
// Populate filter dropdowns
// -----------------------------
function populateFilters() {
    let users = new Set();
    let creators = new Set();

    table.rows().every(function () {
        const data = this.data();
        users.add(data[3]);
        creators.add(data[7]);
    });

    users.forEach(u => $("#fUserName").append(`<option value="${u}">${u}</option>`));
    creators.forEach(c => $("#fCreateBy").append(`<option value="${c}">${c}</option>`));
}
</script>
