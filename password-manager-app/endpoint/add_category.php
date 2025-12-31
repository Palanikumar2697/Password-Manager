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
   INPUT VALIDATION
================================ */

$expenseCategoryName   = trim($_POST['category_name'] ?? '');
$expenseCategoryBudget = trim($_POST['category_budget'] ?? '');

// Required fields
if ($expenseCategoryName === '' || $expenseCategoryBudget === '') {
    redirectWithAlert(
        'warning',
        'Validation Error',
        'Category name and budget are required.'
    );
}

// Budget must be numeric and positive
if (!is_numeric($expenseCategoryBudget) || $expenseCategoryBudget <= 0) {
    redirectWithAlert(
        'warning',
        'Invalid Budget',
        'Budget must be a positive number.'
    );
}

/* ===============================
   CHECK DUPLICATE CATEGORY
================================ */

$stmt = $conn->prepare("
    SELECT 1 
    FROM tbl_expense_category 
    WHERE category_name = :category_name
");
$stmt->execute([
    ':category_name' => $expenseCategoryName
]);

if ($stmt->fetch()) {
    redirectWithAlert(
        'error',
        'Duplicate Category',
        'Category already exists. Please add a different one.'
    );
}

/* ===============================
   INSERT CATEGORY
================================ */

try {
    $stmt = $conn->prepare("
        INSERT INTO tbl_expense_category (category_name, category_budget)
        VALUES (:category_name, :category_budget)
    ");

    $stmt->execute([
        ':category_name'   => $expenseCategoryName,
        ':category_budget' => (int)$expenseCategoryBudget
    ]);

    redirectWithAlert(
        'success',
        'Success',
        'Expense category added successfully!'
    );

} catch (PDOException $e) {
    redirectWithAlert(
        'error',
        'Database Error',
        'Something went wrong. Please try again.'
    );
}
