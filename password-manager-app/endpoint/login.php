<?php

include ('../conn/conn.php');

session_start();

$message = "";
$type = ""; // success | danger | warning
$redirect = "index.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT `tbl_user_id`, `password` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];
        $user_id = $row['tbl_user_id'];

        if (password_verify($password, $stored_password)) {
            $_SESSION['user_id'] = $user_id;
            $message = "✅ Login Successfully!";
            $type = "success";
            $redirect = "http://localhost/PM/password-manager-app/home.php";
        } else {
            $message = "❌ Login Failed, Incorrect Password!";
            $type = "danger";
            $redirect = "http://localhost/PM/password-manager-app/index.php";
            
        }
    } else {
        $message = "⚠️ Login Failed, User Not Found!";
        $type = "warning";
        $redirect = "http://localhost/PM/password-manager-app/index.php";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center" style="height:100vh;">

  <!-- Modal -->
  <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header bg-<?php echo $type; ?> text-white">
          <h5 class="modal-title" id="statusModalLabel">Login Status</h5>
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


