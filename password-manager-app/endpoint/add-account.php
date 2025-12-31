<?php
session_start();

include('../conn/conn.php');
require_once __DIR__ . '/../config/crypto.php';
include('../endpoint/modal_helper.php');

if (!isset($_SESSION['user_id'])) {
    showModal(
        "Authentication Required",
        "⚠️ Please log in before adding an account.",
        "warning",
        "../index.php"
    );
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showModal(
        "Account Status",
        "❌ Invalid request method.",
        "danger",
        "../home.php"
    );
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ---------------- INPUT ---------------- */
$accountName = trim($_POST['account_name'] ?? '');
$username    = trim($_POST['username'] ?? '');
$password    = trim($_POST['password'] ?? '');
$link        = trim($_POST['link'] ?? '');
$description = trim($_POST['description'] ?? '');

/* ---------------- VALIDATION ---------------- */
$errors = [];

if ($accountName === '') {
    $errors[] = "Account name is required.";
}
if ($username === '') {
    $errors[] = "Username is required.";
}
if ($password === '') {
    $errors[] = "Password is required.";
}
if ($link !== '' && !filter_var($link, FILTER_VALIDATE_URL)) {
    $errors[] = "Invalid URL format.";
}

if ($errors) {
    showModal(
        "Validation Error",
        implode("<br>", $errors),
        "warning",
        "../home.php"
    );
    exit;
}

/* 🔐 ENCRYPT PASSWORD */
$encryptedPassword = encryptPassword($password);

try {
    /* ---------------- DUPLICATE CHECK (USER-SCOPED) ---------------- */
    $stmt = $conn->prepare("
        SELECT tbl_account_id
        FROM tbl_accounts
        WHERE username = :username
          AND tbl_user_id = :user_id
    ");
    $stmt->execute([
        ':username' => $username,
        ':user_id'  => $user_id
    ]);

    if ($stmt->fetch()) {
        showModal(
            "Account Exists",
            "⚠️ You already have an account with this username.",
            "warning",
            "../home.php"
        );
        exit;
    }

    $conn->beginTransaction();

    /* ---------------- INSERT ---------------- */
    $insertStmt = $conn->prepare("
        INSERT INTO tbl_accounts
        (tbl_user_id, account_name, username, password, link, description, created_at)
        VALUES
        (:user_id, :account_name, :username, :password, :link, :description, NOW())
    ");

    $insertStmt->execute([
        ':user_id'      => $user_id,
        ':account_name' => $accountName,
        ':username'     => $username,
        ':password'     => $encryptedPassword,
        ':link'         => $link,
        ':description'  => $description
    ]);

    $conn->commit();

    showModal(
        "Account Created",
        "✅ Account added successfully.",
        "success",
        "../home.php"
    );
    exit;

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Log error internally in real apps
    showModal(
        "Database Error",
        "❌ Something went wrong. Please try again.",
        "danger",
        "../home.php"
    );
    exit;
}
