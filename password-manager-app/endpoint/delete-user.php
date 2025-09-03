<?php
include('../conn/conn.php');

if (isset($_GET['user'])) {
    $userID = $_GET['user'];

    try {
        $stmt = $conn->prepare("SELECT `tbl_user_id` FROM `tbl_user` WHERE `tbl_user_id` = :userID");
        $stmt->execute(['userID' => $userID]);
        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($userExists)) {
            $conn->beginTransaction();

            $deleteUserStmt = $conn->prepare("DELETE FROM `tbl_user` WHERE `tbl_user_id` = :userID ");
            $deleteUserStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $deleteUserStmt->execute();

            $deleteAccountsStmt = $conn->prepare("DELETE FROM `tbl_accounts` WHERE `tbl_user_id` = :userID");
            $deleteAccountsStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $deleteAccountsStmt->execute();

            $conn->commit();

            $title    = "Account Status";
            $message  = "✅ User Deleted Successfully. Redirecting to Login...";
            $type     = "success";
            $redirect = "../index.php";

        } else {
            $title    = "Account Status";
            $message  = "⚠️ User account not found.";
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
    $title    = "Invalid Request";
    $message  = "❌ Please select a user to delete.";
    $type     = "danger";
    $redirect = "../home.php";
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
