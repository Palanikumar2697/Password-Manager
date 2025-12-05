<?php
include('../conn/conn.php');
include('../endpoint/modal_helper.php');  // <-- USE THE HELPER
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Get POST data
        $name         = $_POST['name'] ?? '';
        $phoneNumber  = $_POST['phoneNumber'] ?? '';
        $emailAddress = $_POST['emailAddress'] ?? '';
        $username     = $_POST['username'] ?? '';
        $password     = $_POST['password'] ?? '';

        try {
            // Check if user exists
            $stmt = $conn->prepare("SELECT tbl_user_id FROM tbl_user WHERE tbl_user_id = :user_id");
            $stmt->execute([':user_id' => $user_id]);
            $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userExists) {

                $conn->beginTransaction();

                // Update query
                $updateStmt = $conn->prepare("
                    UPDATE tbl_user 
                    SET name = :name,
                        phone_number = :phoneNumber,
                        email_address = :emailAddress,
                        username = :username,
                        password = :password
                    WHERE tbl_user_id = :user_id
                ");

                $updateStmt->execute([
                    ':name'         => $name,
                    ':phoneNumber'  => $phoneNumber,
                    ':emailAddress' => $emailAddress,
                    ':username'     => $username,
                    ':password'     => $password, // ⚠ Hash recommended in production
                    ':user_id'      => $user_id
                ]);

                $conn->commit();

                $title    = "Update Status";
                $message  = "✅ User details updated successfully. Redirecting to Home...";
                $type     = "success";
                $redirect = "../home.php";

            } else {
                $title    = "Update Status";
                $message  = "⚠ User not found.";
                $type     = "warning";
                $redirect = "../home.php";
            }

        } catch (PDOException $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $title    = "Database Error";
            $message  = "❌ " . $e->getMessage();
            $type     = "danger";
            $redirect = "../home.php";
        }

    } else {
        $title    = "Update Failed";
        $message  = "❌ Invalid request.";
        $type     = "danger";
        $redirect = "../home.php";
    }

} else {
    $title    = "Authentication Required";
    $message  = "⚠ Please log in before updating profile.";
    $type     = "warning";
    $redirect = "../index.php";
}
showModal($title, $message, $type, $redirect);

?>
