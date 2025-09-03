<?php
include('../conn/conn.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (isset($_GET['account'])) {
        $accountID = $_GET['account'];

        try {
            $stmt = $conn->prepare("SELECT `tbl_account_id` FROM `tbl_accounts` WHERE `tbl_account_id` = :accountID AND `tbl_user_id` = :user_id");
            $stmt->execute([
                'accountID' => $accountID,
                'user_id'   => $user_id
            ]);
            $accountExists = $stmt->fetch(PDO::FETCH_ASSOC);
var_dump($_GET);
exit;
            if (!empty($accountExists)) {
                $conn->beginTransaction();

                $deleteStmt = $conn->prepare("DELETE FROM `tbl_accounts` WHERE `tbl_account_id` = :accountID AND `tbl_user_id` = :user_id");
                $deleteStmt->bindParam(':accountID', $accountID, PDO::PARAM_INT);
                $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $deleteStmt->execute();

                $conn->commit();

                $title    = "Account Status";
                $message  = "✅ Account Deleted Successfully!";
                $type     = "success";
                $redirect = "../home.php";

            } else {
                $title    = "Account Status";
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
        $title    = "Invalid Request";
        $message  = "❌ Please select an account to delete.";
        $type     = "danger";
        $redirect = "../home.php";
    }

} else {
    $title    = "Authentication";
    $message  = "⚠️ User not logged in. Please log in before deleting an account.";
    $type     = "warning";
    $redirect = "../index.php";
}

// Map type → bootstrap color
$colorMap = [
    "success" => "success",
    "danger"  => "danger",
    "warning" => "warning"
];
$color = $colorMap[$type] ?? "secondary";
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
  <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header bg-<?php echo $color; ?> text-white">
          <h5 class="modal-title"><?php echo $title; ?></h5>
        </div>
        <div class="modal-body">
          <?php echo $message; ?><br>
          <small class="text-muted">Redirecting in 3 seconds...</small>
        </div>
        <div class="modal-footer">
          <a href="<?php echo $redirect; ?>" class="btn btn-<?php echo $color; ?>">OK</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Show modal when page loads
    var statusModal = new bootstrap.Modal(document.getElementById('statusModal'), {
        backdrop: 'static',
        keyboard: false
    });
    statusModal.show();

    // Auto redirect after 3 seconds
    setTimeout(function() {
        window.location.href = "<?php echo $redirect; ?>";
    }, 3000);
  </script>
</body>
</html>

