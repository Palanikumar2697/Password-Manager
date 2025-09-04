<?php
include('../conn/conn.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get POST data safely
        $name         = $_POST['name'] ?? '';
        $phoneNumber  = $_POST['phoneNumber'] ?? '';
        $emailAddress = $_POST['emailAddress'] ?? '';
        $username     = $_POST['username'] ?? '';
        $password     = $_POST['password'] ?? '';

        try {
            // Check if this user exists
            $stmt = $conn->prepare("SELECT tbl_user_id FROM tbl_user WHERE tbl_user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userExists) {
                $conn->beginTransaction();

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
                    ':password'     => $password, // ⚠️ should be hashed in production
                    ':user_id'      => $user_id
                ]);

                $conn->commit();

                $title    = "Update Status";
                $message  = "✅ User details updated successfully. Redirecting to Home...";
                $type     = "success";
                $redirect = "../home.php";

            } else {
                $title    = "Update Status";
                $message  = "⚠️ User not found.";
                $type     = "warning";
                $redirect = "../home.php";
            }

        } catch (PDOException $e) {
            $conn->rollBack();
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
    $message  = "⚠️ Please log in before updating profile.";
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
