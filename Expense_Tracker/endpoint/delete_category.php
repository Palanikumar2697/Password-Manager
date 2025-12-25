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

if (!isset($_GET['category']) || !is_numeric($_GET['category'])) {
    redirectWithAlert(
        'error',
        'Invalid Request',
        'Invalid category ID.'
    );
}

$categoryID = (int)$_GET['category'];

/* ===============================
   CHECK CATEGORY EXISTS
================================ */

$stmt = $conn->prepare("
    SELECT 1 
    FROM tbl_expense_category 
    WHERE tbl_expense_category_id = :id
");
$stmt->execute([':id' => $categoryID]);

if (!$stmt->fetch()) {
    redirectWithAlert(
        'error',
        'Not Found',
        'Category does not exist.'
    );
}

/* ===============================
   CHECK CATEGORY USAGE
================================ */

$stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM tbl_expense 
    WHERE tbl_expense_category_id = :id
");
$stmt->execute([':id' => $categoryID]);

if ($stmt->fetchColumn() > 0) {
    redirectWithAlert(
        'warning',
        'Action Blocked',
        'This category has expenses. Delete expenses first.'
    );
}

/* ===============================
   DELETE CATEGORY
================================ */

try {
    $stmt = $conn->prepare("
        DELETE FROM tbl_expense_category 
        WHERE tbl_expense_category_id = :id
    ");

    $stmt->execute([':id' => $categoryID]);

    redirectWithAlert(
        'success',
        'Deleted',
        'Category deleted successfully!'
    );

} catch (PDOException $e) {
    redirectWithAlert(
        'error',
        'Database Error',
        'Unable to delete category.'
    );
}
