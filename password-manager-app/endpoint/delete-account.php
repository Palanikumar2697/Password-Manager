<?php
include('../conn/conn.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?status=warning&msg=" . urlencode("Please log in before deleting an account."));
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Validate ID parameter
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../home.php?status=danger&msg=" . urlencode("Invalid account ID!"));
    exit();
}

$accountID = (int) $_GET['id'];

try {
    // Check if account exists & belongs to this user
    $stmt = $conn->prepare("
        SELECT tbl_account_id 
        FROM tbl_accounts 
        WHERE tbl_account_id = :accountID 
          AND tbl_user_id = :user_id
    ");
    $stmt->execute(['accountID' => $accountID, 'user_id' => $user_id]);

    if ($stmt->fetch()) {
        $conn->beginTransaction();

        $deleteStmt = $conn->prepare("
            DELETE FROM tbl_accounts 
            WHERE tbl_account_id = :accountID 
              AND tbl_user_id = :user_id
        ");
        $deleteStmt->execute(['accountID' => $accountID, 'user_id' => $user_id]);

        $conn->commit();

        // ✅ Redirect with success
        header("Location: ../home.php?status=success&msg=" . urlencode("✅ Account deleted successfully!"));
        exit();
    } else {
        header("Location: ../home.php?status=warning&msg=" . urlencode("⚠️ Account not found or does not belong to you."));
        exit();
    }
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    header("Location: ../home.php?status=danger&msg=" . urlencode("❌ Database Error: " . $e->getMessage()));
    exit();
}
?>
