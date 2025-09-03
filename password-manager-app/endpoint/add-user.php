<?php
include ('../conn/conn.php');

$message = "";
$type = ""; // success | danger | warning
$redirect = "http://localhost/PM/password-manager-app/index.php"; // default

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phoneNumber = $_POST['phone_number'];
    $emailAddress = $_POST['email_address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirmPassword = $_POST['confirm_password'];

    try {
        $stmt = $conn->prepare("SELECT `username` FROM `tbl_user` WHERE `username` = :username");
        $stmt->execute(['username' => $username]);
        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($userExists)) {
            $conn->beginTransaction();

            $insertStmt = $conn->prepare("
                INSERT INTO `tbl_user` 
                (`tbl_user_id`, `name`, `phone_number`, `email_address`, `username`, `password`) 
                VALUES (NULL, :name, :phone_number, :email_address, :username, :password)
            ");
            $insertStmt->bindParam(':name', $name, PDO::PARAM_STR);
            $insertStmt->bindParam(':phone_number', $phoneNumber, PDO::PARAM_STR);
            $insertStmt->bindParam(':email_address', $emailAddress, PDO::PARAM_STR);
            $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
            $insertStmt->bindParam(':password', $password, PDO::PARAM_STR);
            $insertStmt->execute();

            $conn->commit();

            $message = "✅ User Registered Successfully!";
            $type = "success";
            $redirect = "http://localhost/PM/password-manager-app/index.php";
        } else {
            $message = "⚠️ User Already Exists!";
            $type = "warning";
            $redirect = "http://localhost/PM/password-manager-app/index.php";
        }
    } catch (PDOException $e) {
        $message = "❌ Error: " . $e->getMessage();
        $type = "danger";
        $redirect = "http://localhost/PM/password-manager-app/index.php";
    }
} else {
    $message = "❌ Account Registration Failed!";
    $type = "danger";
    $redirect = "http://localhost/PM/password-manager-app/index.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center" style="height:100vh;">

  <!-- Modal -->
  <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header bg-<?php echo $type; ?> text-white">
          <h5 class="modal-title" id="statusModalLabel">Registration Status</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
    myModal.show();

    // Auto redirect after 3 seconds
    setTimeout(function() {
        window.location.href = "<?php echo $redirect; ?>";
    }, 3000);
  </script>
</body>
</html>

