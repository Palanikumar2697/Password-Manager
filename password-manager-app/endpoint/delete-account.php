<?php
session_start();

include('../conn/conn.php');

header('Content-Type: application/json');

/* ---------------- AUTH CHECK ---------------- */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status'  => 'warning',
        'message' => 'Please log in before deleting an account.'
    ]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ---------------- INPUT VALIDATION ---------------- */
if (empty($_POST['id']) || !ctype_digit($_POST['id'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid account ID.'
    ]);
    exit;
}

$accountID = (int) $_POST['id'];

try {
    $conn->beginTransaction();

    /* ---------------- OWNERSHIP CHECK ---------------- */
    $stmt = $conn->prepare("
        SELECT account_name
        FROM tbl_accounts
        WHERE tbl_account_id = :accountID
          AND tbl_user_id    = :user_id
    ");
    $stmt->execute([
        ':accountID' => $accountID,
        ':user_id'   => $user_id
    ]);

    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        $conn->rollBack();
        echo json_encode([
            'status'  => 'warning',
            'message' => 'Account not found or unauthorized access.'
        ]);
        exit;
    }

    /* ---------------- DELETE ---------------- */
    $deleteStmt = $conn->prepare("
        DELETE FROM tbl_accounts
        WHERE tbl_account_id = :accountID
          AND tbl_user_id    = :user_id
    ");
    $deleteStmt->execute([
        ':accountID' => $accountID,
        ':user_id'   => $user_id
    ]);

    $conn->commit();

    echo json_encode([
        'status'  => 'success',
        'message' => 'Account "' . $account['account_name'] . '" deleted successfully!',
        'id'      => $accountID
    ]);
    exit;

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log('Delete Account Error: ' . $e->getMessage());

    echo json_encode([
        'status'  => 'error',
        'message' => 'Database error occurred.'
    ]);
    exit;
}
