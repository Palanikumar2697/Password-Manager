<?php
session_start();

include('../conn/conn.php');
include('../endpoint/modal_helper.php');
include('../config/crypto.php');

if (!isset($_SESSION['user_id'])) {
    showModal(
        "Authentication Required",
        "⚠️ Please log in first.",
        "warning",
        "../index.php"
    );
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showModal(
        "Update Failed",
        "❌ Invalid request method.",
        "danger",
        "../home.php"
    );
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* -----------------------------------------
   INPUT & VALIDATION
----------------------------------------- */
$accountID   = trim($_POST['tbl_account_id'] ?? '');
$accountName = trim($_POST['account_name'] ?? '');
$username    = trim($_POST['username'] ?? '');
$password    = trim($_POST['password'] ?? '');
$link        = trim($_POST['link'] ?? '');
$description = trim($_POST['description'] ?? '');

$errors = [];

if (!ctype_digit($accountID)) {
    $errors[] = "Invalid Account ID.";
}
if ($accountName === '') {
    $errors[] = "Account name is required.";
}
if ($username === '') {
    $errors[] = "Username is required.";
}
if ($link !== '' && !filter_var($link, FILTER_VALIDATE_URL)) {
    $errors[] = "Link is not a valid URL.";
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

$accountID = (int) $accountID;

/* -----------------------------------------
   OWNERSHIP CHECK
----------------------------------------- */
$stmt = $conn->prepare("
    SELECT tbl_account_id
    FROM tbl_accounts
    WHERE tbl_account_id = :accountID
      AND tbl_user_id    = :user_id
");
$stmt->execute([
    ':accountID' => $accountID,
    ':user_id'   => $user_id
]);

if (!$stmt->fetch()) {
    showModal(
        "Update Failed",
        "⚠️ Account not found or unauthorized access.",
        "warning",
        "../home.php"
    );
    exit;
}

/* -----------------------------------------
   UPDATE ACCOUNT
----------------------------------------- */
try {
    $conn->beginTransaction();

    if ($password !== '') {
        $encryptedPassword = encryptPassword($password);

        $sql = "
            UPDATE tbl_accounts SET
                account_name = :account_name,
                username     = :username,
                password     = :password,
                link         = :link,
                description  = :description,
                updated_at   = NOW()
            WHERE tbl_account_id = :accountID
              AND tbl_user_id    = :user_id
        ";
    } else {
        $sql = "
            UPDATE tbl_accounts SET
                account_name = :account_name,
                username     = :username,
                link         = :link,
                description  = :description,
                updated_at   = NOW()
            WHERE tbl_account_id = :accountID
              AND tbl_user_id    = :user_id
        ";
    }

    $params = [
        ':account_name' => $accountName,
        ':username'     => $username,
        ':link'         => $link,
        ':description'  => $description,
        ':accountID'    => $accountID,
        ':user_id'      => $user_id
    ];

    if ($password !== '') {
        $params[':password'] = $encryptedPassword;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $conn->commit();

    showModal(
        "Update Successful",
        "✅ Account updated successfully.",
        "success",
        "../home.php"
    );
    exit;

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Log error in real apps
    showModal(
        "Database Error",
        "❌ Something went wrong. Please try again.",
        "danger",
        "../home.php"
    );
    exit;
}
