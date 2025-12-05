<?php
include ('../conn/conn.php');
include('../endpoint/modal_helper.php');  // <-- USE THE HELPER, NOT status.php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accountName = $_POST['account_name'];
        $username    = $_POST['username'];
        $password    = $_POST['password'];
        $link        = $_POST['link'];
        $description = $_POST['description'];

        if (!empty($_POST['created_at'])) {
            $created_at = date("Y-m-d H:i:s", strtotime($_POST['created_at']));
        } else {
            $created_at = date("Y-m-d H:i:s");
        }

        try {
            $stmt = $conn->prepare("SELECT `username` FROM `tbl_accounts` WHERE `username` = :username");
            $stmt->execute(['username' => $username]);
            $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($userExists)) {
                $conn->beginTransaction();

                $insertStmt = $conn->prepare("
                    INSERT INTO `tbl_accounts` 
                    (`tbl_account_id`, `tbl_user_id`, `account_name`, `username`, `password`, `link`, `description`, `created_at`) 
                    VALUES (NULL, :user_id, :account_name, :username, :password, :link, :description, :created_at)
                ");
                $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':account_name', $accountName, PDO::PARAM_STR);
                $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
                $insertStmt->bindParam(':password', $password, PDO::PARAM_STR);
                $insertStmt->bindParam(':link', $link, PDO::PARAM_STR);
                $insertStmt->bindParam(':description', $description, PDO::PARAM_STR);
                $insertStmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);

                $insertStmt->execute();
                $conn->commit();

                $title    = "Account Status";
                $message  = "✅ Account Created Successfully!";
                $type     = "success";
                $redirect = "../home.php";

            } else {
                $title    = "Account Status";
                $message  = "⚠️ Username Already Exists!";
                $type     = "warning";
                $redirect = "../home.php";
            }

        } catch (PDOException $e) {
            $title    = "Database Error";
            $message  = "❌ " . $e->getMessage();
            $type     = "danger";
            $redirect = "../home.php";
        }

    } else {
        $title    = "Account Status";
        $message  = "❌ Account Creation Failed!";
        $type     = "danger";
        $redirect = "../home.php";
    }

} else {
    $title    = "Authentication";
    $message  = "⚠️ User not logged in. Please log in before adding an account.";
    $type     = "warning";
    $redirect = "../index.php";
}
?>

