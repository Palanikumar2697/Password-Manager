<?php
include('../conn/conn.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
        $accountID = (int) $_GET['id'];

        try {
            // Fetch account name
            $stmt = $conn->prepare("SELECT account_name 
                                    FROM tbl_accounts 
                                    WHERE tbl_account_id = :accountID 
                                      AND tbl_user_id   = :user_id");
            $stmt->execute(['accountID' => $accountID, 'user_id' => $user_id]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($account) {
                $accountName = $account['account_name'];

                $conn->beginTransaction();

                $deleteStmt = $conn->prepare("DELETE FROM tbl_accounts 
                                              WHERE tbl_account_id = :accountID 
                                                AND tbl_user_id   = :user_id");
                $deleteStmt->execute(['accountID' => $accountID, 'user_id' => $user_id]);

                $conn->commit();

                // ✅ Flash success message with account name
                $_SESSION['flash_status'] = "success";
                $_SESSION['flash_msg'] = "Account \"{$accountName}\" deleted successfully!";
            } else {
                $_SESSION['flash_status'] = "warning";
                $_SESSION['flash_msg'] = "Account not found or does not belong to you.";
            }
        } catch (PDOException $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            $_SESSION['flash_status'] = "error";
            $_SESSION['flash_msg'] = "Database Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['flash_status'] = "error";
        $_SESSION['flash_msg'] = "Invalid account ID!";
    }
} else {
    $_SESSION['flash_status'] = "warning";
    $_SESSION['flash_msg'] = "Please log in before deleting an account.";
}

header("Location: ../home.php");
exit();
?>
