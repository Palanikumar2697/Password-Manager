<?php
include('../conn/conn.php');

session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accountID   = $_POST['tbl_account_id']; 
        $accountName = $_POST['account_name'];
        $username    = $_POST['username'];
        $password    = $_POST['password'];
        $link        = $_POST['link'];
        $description = $_POST['description'];

        try {
            $stmt = $conn->prepare("SELECT `tbl_account_id` FROM `tbl_accounts` WHERE `tbl_account_id` = :accountID AND `tbl_user_id` = :user_id");
            $stmt->execute([
                'accountID' => $accountID,
                'user_id'   => $user_id
            ]);
            $accountExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($accountExists)) {
                $conn->beginTransaction();

                $updateStmt = $conn->prepare("
                    UPDATE `tbl_accounts` 
                    SET `account_name` = :account_name, 
                        `username` = :username, 
                        `password` = :password, 
                        `link` = :link, 
                        `description` = :description
                    WHERE `tbl_account_id` = :accountID 
                      AND `tbl_user_id` = :user_id
                ");
                $updateStmt->bindParam(':account_name', $accountName, PDO::PARAM_STR);
                $updateStmt->bindParam(':username', $username, PDO::PARAM_STR);
                $updateStmt->bindParam(':password', $password, PDO::PARAM_STR);
                $updateStmt->bindParam(':link', $link, PDO::PARAM_STR);
                $updateStmt->bindParam(':description', $description, PDO::PARAM_STR);
                $updateStmt->bindParam(':accountID', $accountID, PDO::PARAM_INT);
                $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $updateStmt->execute();

                $conn->commit();

                $title    = "Update Status";
                $message  = "✅ Account Updated Successfully. Redirecting to Home...";
                $type     = "success";
                $redirect = "../home.php";

            } else {
                $title    = "Update Status";
                $message  = "⚠️ Account not found or does not belong to the current user.";
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
        $title    = "Update Failed";
        $message  = "❌ Account Update Failed!";
        $type     = "danger";
        $redirect = "../home.php";
    }

} else {
    $title    = "Authentication Required";
    $message  = "⚠️ User not logged in. Please log in before updating an account.";
    $type     = "warning";
    $redirect = "../index.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center" style="height:100vh;">

  <!-- Modal -->
  <div class="modal fade show" id="statusModal" tabindex="-1" aria-hidden="true" style="display:block; background: rgba(0,0,0,0.6);">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header bg-<?php echo $type; ?> text-white">
          <h5 class="modal-title"><?php echo $title; ?></h5>
        </div>
        <div class="modal-body">
          <?php echo $message; ?><br>
          <small class="text-muted">Redirecting in 3 seconds...</small>
        </div>
        <div class="modal-footer">
          <a href="<?php echo $redirect; ?>" class="btn btn-<?php echo $type; ?>">OK</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Auto redirect after 3 seconds
    setTimeout(function() {
        window.location.href = "<?php echo $redirect; ?>";
    }, 3000);
  </script>
</body>
</html>
