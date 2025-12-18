<?php
include('../conn/conn.php');
include('../endpoint/modal_helper.php');  
include('../config/crypto.php'); // if you encrypt account passwords

session_start();

if (!isset($_SESSION['user_id'])) {
    showModal("Authentication Required", "⚠️ Please log in first.", "warning", "../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showModal("Update Failed", "❌ Invalid request method.", "danger", "../home.php");
    exit;
}

/* -----------------------------------------
   VALIDATION
----------------------------------------- */
$accountID   = trim($_POST['tbl_account_id'] ?? '');
$accountName = trim($_POST['account_name'] ?? '');
$username    = trim($_POST['username'] ?? '');
$password    = trim($_POST['password'] ?? '');
$link        = trim($_POST['link'] ?? '');
$description = trim($_POST['description'] ?? '');
$created_at_input = trim($_POST['created_at'] ?? '');

$errors = [];

// Validate Account ID
if (empty($accountID) || !is_numeric($accountID)) {
    $errors[] = "Invalid Account ID.";
}

// Validate account name
if (empty($accountName)) {
    $errors[] = "Account name is required.";
}

// Validate username
if (empty($username)) {
    $errors[] = "Username is required.";
}



// Validate link if provided
if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
    $errors[] = "Link is not a valid URL.";
}

// Validate created_at date
if (!empty($created_at_input)) {
    $timestamp = strtotime($created_at_input);
    if ($timestamp === false) {
        $errors[] = "Invalid date format.";
    } else {
        $created_at = date("Y-m-d H:i:s", $timestamp);
    }
} else {
    $created_at = date("Y-m-d H:i:s");
}

// If validation fails
if (!empty($errors)) {
    showModal("Validation Error", implode("<br>", $errors), "warning", "../home.php");
    exit;
}

/* -----------------------------------------
   CHECK IF ACCOUNT BELONGS TO USER
----------------------------------------- */
try {
    $stmt = $conn->prepare("
        SELECT tbl_account_id 
        FROM tbl_accounts 
        WHERE tbl_account_id = :accountID AND tbl_user_id = :user_id
    ");
    $stmt->execute([
        'accountID' => $accountID,
        'user_id'   => $user_id
    ]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        setModal("Update Failed", "⚠️ Account not found or unauthorized access.", "warning", "../home.php");
        exit;
    }

    /* -----------------------------------------
       UPDATE ACCOUNT
    ----------------------------------------- */
    
  $conn->beginTransaction();

if (!empty($password)) {
    // User entered new password → encrypt & update
    $encryptedPassword = encryptPassword($password);

    $updateStmt = $conn->prepare("
        UPDATE tbl_accounts SET
            account_name = :account_name,
            username     = :username,
            password     = :password,
            link         = :link,
            description  = :description,
            created_at   = :created_at
        WHERE tbl_account_id = :accountID 
          AND tbl_user_id    = :user_id
    ");

    $updateStmt->execute([
        ':account_name' => $accountName,
        ':username'     => $username,
        ':password'     => $encryptedPassword,
        ':link'         => $link,
        ':description'  => $description,
        ':created_at'   => $created_at,
        ':accountID'    => $accountID,
        ':user_id'      => $user_id
    ]);

} else {
    // Password not changed
    $updateStmt = $conn->prepare("
        UPDATE tbl_accounts SET
            account_name = :account_name,
            username     = :username,
            link         = :link,
            description  = :description,
            created_at   = :created_at
        WHERE tbl_account_id = :accountID 
          AND tbl_user_id    = :user_id
    ");

    $updateStmt->execute([
        ':account_name' => $accountName,
        ':username'     => $username,
        ':link'         => $link,
        ':description'  => $description,
        ':created_at'   => $created_at,
        ':accountID'    => $accountID,
        ':user_id'      => $user_id
    ]);
}

$conn->commit();


    showModal(
        "Update Successful",
        "✅ Account Updated Successfully. Redirecting to Home...",
        "success",
        "../home.php"
    );

} catch (PDOException $e) {
    $conn->rollBack();
    showModal("Database Error", "❌ " . $e->getMessage(), "danger", "../home.php");
}
?>
