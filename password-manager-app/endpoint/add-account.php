<?php
include ('../conn/conn.php');
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
