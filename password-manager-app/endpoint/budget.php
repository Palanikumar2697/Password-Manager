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

$budgetID      = 1; // fixed row
$monthlyBudget = trim($_POST['monthly_budget'] ?? '');

// Required
if ($monthlyBudget === '') {
    redirectWithAlert(
        'warning',
        'Validation Error',
        'Monthly budget is required.'
    );
}

// Numeric & positive
if (!is_numeric($monthlyBudget) || $monthlyBudget <= 0) {
    redirectWithAlert(
        'warning',
        'Invalid Budget',
        'Monthly budget must be a positive number.'
    );
}

/* ===============================
   UPDATE BUDGET
================================ */

try {
    $stmt = $conn->prepare("
        UPDATE tbl_budget 
        SET monthly_budget = :monthly_budget
        WHERE tbl_budget_id = :budget_id
    ");

    $stmt->execute([
        ':monthly_budget' => (int)$monthlyBudget,
        ':budget_id'      => $budgetID
    ]);

    redirectWithAlert(
        'success',
        'Success',
        'Monthly budget updated successfully!'
    );

} catch (PDOException $e) {
    redirectWithAlert(
        'error',
        'Database Error',
        'Something went wrong. Please try again.'
    );
}
