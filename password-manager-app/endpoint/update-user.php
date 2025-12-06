<?php
include('../conn/conn.php');
include('../endpoint/modal_helper.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    showModal("Authentication Required", "⚠ Please log in before updating profile.", "warning", "../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showModal("Update Failed", "❌ Invalid request.", "danger", "../home.php");
    exit;
}

try {
    // Fetch existing user info including password
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE tbl_user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        showModal("Update Status", "⚠ User not found.", "warning", "../home.php");
        exit;
    }

    // Get POST data
    $name         = $_POST['name'] ?? '';
    $phoneNumber  = $_POST['phoneNumber'] ?? '';
    $emailAddress = $_POST['emailAddress'] ?? '';
    $username     = $_POST['username'] ?? '';
    $password     = $_POST['password'] ?? '';

    // ====== IMPORTANT PART ======
    // If password field is empty → keep old password
    if (empty($password)) {
        $password = $user['password'];
    }
    // ============================

    $conn->beginTransaction();

    // Update user
    $update = $conn->prepare("
        UPDATE tbl_user 
        SET name = :name,
            phone_number = :phoneNumber,
            email_address = :emailAddress,
            username = :username,
            password = :password
        WHERE tbl_user_id = :user_id
    ");

    $update->execute([
        ':name'         => $name,
        ':phoneNumber'  => $phoneNumber,
        ':emailAddress' => $emailAddress,
        ':username'     => $username,
        ':password'     => $password,  // no hashing as requested
        ':user_id'      => $user_id
    ]);

    $conn->commit();

    showModal(
        "Update Status",
        "✅ User details updated successfully. Redirecting to Home...",
        "success",
        "../home.php"
    );

} catch (PDOException $e) {

    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    showModal("Database Error", "❌ " . $e->getMessage(), "danger", "../home.php");
}
?>
