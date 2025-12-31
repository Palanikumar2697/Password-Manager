<?php
include('../conn/conn1.php');

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
                window.location.href = '../Expense_Dashboard.php';
            });
        </script>
    </body>
    </html>";
    exit;
}

/* ===============================
   VALIDATE POST REQUEST
================================ */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithAlert('error', 'Invalid Request', 'Invalid request method.');
}

/* ===============================
   COLLECT & VALIDATE INPUTS
================================ */
$expenseID          = $_POST['tbl_expense'] ?? '';
$expenseName        = trim($_POST['expense_name'] ?? '');
$expenseCategoryID  = $_POST['tbl_expense_category_id'] ?? '';
$expenseDate        = $_POST['expense_date'] ?? '';
$expenseSpent       = $_POST['expense_spent'] ?? '';
$expenseDescription = trim($_POST['expense_description'] ?? '');

// Required fields
if ($expenseID === '' || $expenseName === '' || $expenseCategoryID === '' || $expenseDate === '' || $expenseSpent === '') {
    redirectWithAlert('warning', 'Validation Error', 'All required fields must be filled.');
}

// Validate numeric fields
if (!is_numeric($expenseID) || !is_numeric($expenseCategoryID) || !is_numeric($expenseSpent) || $expenseSpent < 0) {
    redirectWithAlert('warning', 'Invalid Input', 'Expense ID, Category, and Spent must be valid numbers.');
}

// Validate date
if (!DateTime::createFromFormat('Y-m-d', $expenseDate)) {
    redirectWithAlert('warning', 'Invalid Date', 'Please provide a valid date.');
}

/* ===============================
   CHECK EXPENSE EXISTS
================================ */
$stmt = $conn->prepare("SELECT 1 FROM tbl_expense WHERE tbl_expense_id = :id");
$stmt->execute([':id' => $expenseID]);

if (!$stmt->fetch()) {
    redirectWithAlert('error', 'Not Found', 'Expense does not exist.');
}

/* ===============================
   UPDATE EXPENSE
================================ */
try {
    $stmt = $conn->prepare("
        UPDATE tbl_expense SET
            expense_name = :expenseName,
            tbl_expense_category_id = :expenseCategoryID,
            expense_date = :expenseDate,
            expense_spent = :expenseSpent,
            expense_description = :expenseDescription
        WHERE tbl_expense_id = :expenseID
    ");

    $stmt->execute([
        ':expenseName'        => $expenseName,
        ':expenseCategoryID'  => (int)$expenseCategoryID,
        ':expenseDate'        => $expenseDate,
        ':expenseSpent'       => (int)$expenseSpent,
        ':expenseDescription' => $expenseDescription,
        ':expenseID'          => (int)$expenseID
    ]);

    redirectWithAlert('success', 'Updated', 'Expense updated successfully!');

} catch (PDOException $e) {
    redirectWithAlert('error', 'Database Error', 'Unable to update expense.');
}
