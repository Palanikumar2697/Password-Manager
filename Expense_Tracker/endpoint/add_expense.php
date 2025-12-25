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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithAlert('error', 'Invalid Request', 'Invalid request method.');
}

/* ===============================
   INPUT VALIDATION
================================ */

$categoryID          = $_POST['tbl_expense_category_id'] ?? '';
$expenseName         = trim($_POST['expense_name'] ?? '');
$expenseDateRaw      = $_POST['expense_date'] ?? '';
$expenseSpent        = $_POST['expense_spent'] ?? '';
$expenseDescription  = trim($_POST['expense_description'] ?? '');

// Required fields
if (
    $categoryID === '' ||
    $expenseName === '' ||
    $expenseDateRaw === '' ||
    $expenseSpent === ''
) {
    redirectWithAlert(
        'warning',
        'Validation Error',
        'All required fields must be filled.'
    );
}

// Validate date
$expenseDate = date('Y-m-d', strtotime($expenseDateRaw));
if (!$expenseDate || $expenseDate === '1970-01-01') {
    redirectWithAlert(
        'warning',
        'Invalid Date',
        'Please select a valid date.'
    );
}

// Validate amount
if (!is_numeric($expenseSpent) || $expenseSpent <= 0) {
    redirectWithAlert(
        'warning',
        'Invalid Amount',
        'Expense amount must be a positive number.'
    );
}

// Validate category exists
$stmt = $conn->prepare("
    SELECT 1 
    FROM tbl_expense_category 
    WHERE tbl_expense_category_id = :id
");
$stmt->execute([':id' => $categoryID]);

if (!$stmt->fetch()) {
    redirectWithAlert(
        'error',
        'Invalid Category',
        'Selected category does not exist.'
    );
}

/* ===============================
   INSERT EXPENSE
================================ */

try {
    $stmt = $conn->prepare("
        INSERT INTO tbl_expense 
        (tbl_expense_category_id, expense_name, expense_date, expense_spent, expense_description)
        VALUES (:categoryID, :expenseName, :expenseDate, :expenseSpent, :expenseDescription)
    ");

    $stmt->execute([
        ':categoryID'         => (int)$categoryID,
        ':expenseName'        => $expenseName,
        ':expenseDate'        => $expenseDate,
        ':expenseSpent'       => (int)$expenseSpent,
        ':expenseDescription' => $expenseDescription
    ]);

    redirectWithAlert(
        'success',
        'Success',
        'Expense added successfully!'
    );

} catch (PDOException $e) {
    redirectWithAlert(
        'error',
        'Database Error',
        'Something went wrong. Please try again.'
    );
}
