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
   INPUT VALIDATION
================================ */
$updateCategoryID = $_POST['tbl_expense_category_id'] ?? '';
$updateCategoryName = trim($_POST['category_name'] ?? '');
$updateCategoryBudget = trim($_POST['category_budget'] ?? '');

// Required fields
if ($updateCategoryID === '' || $updateCategoryName === '' || $updateCategoryBudget === '') {
    redirectWithAlert('warning', 'Validation Error', 'All fields are required.');
}

// Numeric & positive budget
if (!is_numeric($updateCategoryBudget) || $updateCategoryBudget <= 0) {
    redirectWithAlert('warning', 'Invalid Budget', 'Category budget must be a positive number.');
}

// Validate category exists
$stmt = $conn->prepare("SELECT 1 FROM tbl_expense_category WHERE tbl_expense_category_id = :id");
$stmt->execute([':id' => $updateCategoryID]);

if (!$stmt->fetch()) {
    redirectWithAlert('error', 'Not Found', 'Category does not exist.');
}

/* ===============================
   UPDATE CATEGORY
================================ */
try {
    $stmt = $conn->prepare("
        UPDATE tbl_expense_category
        SET category_name = :name, category_budget = :budget
        WHERE tbl_expense_category_id = :id
    ");

    $stmt->execute([
        ':name'   => $updateCategoryName,
        ':budget' => (int)$updateCategoryBudget,
        ':id'     => (int)$updateCategoryID
    ]);

    redirectWithAlert('success', 'Updated', 'Category updated successfully!');

} catch (PDOException $e) {
    redirectWithAlert('error', 'Database Error', 'Unable to update category.');
}
