<?php
include('../conn/conn.php');
session_start();

header('Content-Type: application/json'); // ✅ tell browser we return JSON

$response = ['status' => 'error', 'message' => 'Something went wrong'];

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

                $response = [
                    'status'  => 'success',
                    'message' => "Account \"{$accountName}\" deleted successfully!",
                    'id'      => $accountID
                ];
            } else {
                $response = [
                    'status'  => 'warning',
                    'message' => "Account not found or does not belong to you."
                ];
            }
        } catch (PDOException $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            $response = [
                'status'  => 'error',
                'message' => "Database Error: " . $e->getMessage()
            ];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Invalid account ID!'];
    }
} else {
    $response = ['status' => 'warning', 'message' => 'Please log in before deleting an account.'];
}

echo json_encode($response); // ✅ send JSON back
exit();
