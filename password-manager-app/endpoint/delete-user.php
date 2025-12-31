<?php
session_start();

include('../conn/conn.php');
include('../endpoint/modal_helper.php');

/* ---------------- AUTH CHECK ---------------- */
if (!isset($_SESSION['user_id'])) {
    showModal(
        "Authentication Required",
        "⚠️ Please log in first.",
        "warning",
        "../index.php"
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    showModal(
        "Invalid Request",
        "❌ Invalid request method.",
        "danger",
        "../home.php"
    );
    exit;
}

$userID = (int) $_SESSION['user_id'];

try {
    $conn->beginTransaction();

    /* ---------------- DELETE USER ACCOUNTS FIRST ---------------- */
    $deleteAccounts = $conn->prepare("
        DELETE FROM tbl_accounts
        WHERE tbl_user_id = :userID
    ");
    $deleteAccounts->execute([':userID' => $userID]);

    /* ---------------- DELETE USER ---------------- */
    $deleteUser = $conn->prepare("
        DELETE FROM tbl_user
        WHERE tbl_user_id = :userID
    ");
    $deleteUser->execute([':userID' => $userID]);

    $conn->commit();

    /* ---------------- LOGOUT ---------------- */
    session_destroy();

    showModal(
        "Account Deleted",
        "✅ Your account has been permanently deleted.",
        "success",
        "../index.php"
    );
    exit;

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log("User Delete Error: " . $e->getMessage());

    showModal(
        "Database Error",
        "❌ Something went wrong. Please try again later.",
        "danger",
        "../home.php"
    );
    exit;
}
