<?php
include('../conn/conn.php');

function redirectWithAlert($type, $title, $message) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$type',
                title: '$title',
                text: '$message',
                confirmButtonColor: '#343a40'
            }).then(() => {
                window.location.href = 'http://localhost/Expense_Tracker/';
            });
        </script>
    </body>
    </html>";
    exit;
}

/* ===============================
   VALIDATE REQUEST
================================ */

if (!isset($_GET['expense']) || !is_numeric($_GET['expense'])) {
    redirectWithAlert(
        'error',
        'Invalid Request',
        'Invalid expense ID.'
    );
}

$expenseID = (int)$_GET['expense'];

/* ===============================
   CHECK EXPENSE EXISTS
================================ */

$stmt = $conn->prepare("
    SELECT 1 
    FROM tbl_expense 
    WHERE tbl_expense_id = :id
");
$stmt->execute([':id' => $expenseID]);

if (!$stmt->fetch()) {
    redirectWithAlert(
        'error',
        'Not Found',
        'Expense does not exist.'
    );
}

/* ===============================
   DELETE EXPENSE
================================ */

try {
    $stmt = $conn->prepare("
        DELETE FROM tbl_expense 
        WHERE tbl_expense_id = :id
    ");

    $stmt->execute([':id' => $expenseID]);

    redirectWithAlert(
        'success',
        'Deleted',
        'Expense deleted successfully!'
    );

} catch (PDOException $e) {
    redirectWithAlert(
        'error',
        'Database Error',
        'Unable to delete expense.'
    );
}
