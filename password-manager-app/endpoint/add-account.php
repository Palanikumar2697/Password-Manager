<?php
session_start();

include('../conn/conn.php');
require_once __DIR__ . '/../config/crypto.php'; // ✅ REQUIRED
include('../endpoint/modal_helper.php');

if (!isset($_SESSION['user_id'])) {
    showModal(
        "Authentication",
        "⚠️ User not logged in. Please log in before adding an account.",
        "warning",
        "../index.php"
    );
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showModal(
        "Account Status",
        "❌ Invalid request method!",
        "danger",
        "../home.php"
    );
    exit;
}

$user_id     = $_SESSION['user_id'];
$accountName = trim($_POST['account_name'] ?? '');
$username    = trim($_POST['username'] ?? '');
$password    = trim($_POST['password'] ?? '');
$link        = trim($_POST['link'] ?? '');
$description = trim($_POST['description'] ?? '');

$created_at = !empty($_POST['created_at'])
    ? date("Y-m-d H:i:s", strtotime($_POST['created_at']))
    : date("Y-m-d H:i:s");

/* ---------------- VALIDATION ---------------- */
if ($accountName === '' || $username === '' || $password === '') {
    showModal(
        "Validation Error",
        "⚠️ Account name, username and password are required.",
        "warning",
        "../home.php"
    );
    exit;
}

/* 🔐 ENCRYPT PASSWORD */
$encryptedPassword = encryptPassword($password);

try {
    // Check duplicate username
    $stmt = $conn->prepare("
        SELECT tbl_account_id 
        FROM tbl_accounts 
        WHERE username = :username
    ");
    $stmt->execute(['username' => $username]);

    if ($stmt->fetch()) {
        showModal(
            "Account Status",
            "⚠️ Username already exists!",
            "warning",
            "../home.php"
        );
        exit;
    }

    $conn->beginTransaction();

    $insertStmt = $conn->prepare("
        INSERT INTO tbl_accounts 
        (tbl_user_id, account_name, username, password, link, description, created_at)
        VALUES
        (:user_id, :account_name, :username, :password, :link, :description, :created_at)
    ");

    $insertStmt->execute([
        ':user_id'      => $user_id,
        ':account_name' => $accountName,
        ':username'     => $username,
        ':password'     => $encryptedPassword, // ✅ encrypted value
        ':link'         => $link,
        ':description'  => $description,
        ':created_at'   => $created_at
    ]);

    $conn->commit();

    showModal(
        "Account Status",
        "✅ Account Created Successfully!",
        "success",
        "../home.php"
    );

} catch (PDOException $e) {
    $conn->rollBack();

    showModal(
        "Database Error",
        "❌ " . $e->getMessage(),
        "danger",
        "../home.php"
    );
}
