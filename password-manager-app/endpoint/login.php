<?php
session_start();
include('../conn/conn.php');
include('../endpoint/modal_helper.php');

$loginPage = "http://localhost/PM/password-manager-app/index.php";
$homePage  = "../home.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: $loginPage");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    showModal1("Input Required", "⚠️ Username and Password cannot be empty!", "warning", $loginPage);
    exit;
}

// Fetch user
$stmt = $conn->prepare("SELECT tbl_user_id, password FROM tbl_user WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    showModal1("User Not Found", "⚠️ No account found with that username.", "warning", $loginPage);
    exit;
}

// ✅ FIXED PASSWORD CHECK
if (!password_verify($password, $user['password'])) {
    showModal1("Login Failed", "❌ Incorrect Password!", "danger", $loginPage);
    exit;
}

// ✅ Login success
$_SESSION['user_id'] = $user['tbl_user_id'];
$_SESSION['username'] = $username;
$_SESSION['login_time'] = time();
$_SESSION['last_activity'] = time();
$_SESSION['flash_status'] = "success";
$_SESSION['flash_msg'] = "✅ Login Successfully!";

// Update last login
$update = $conn->prepare("
    UPDATE tbl_user SET last_login = NOW() WHERE tbl_user_id = :id
");
$update->execute(['id' => $user['tbl_user_id']]);

header("Location: $homePage");
exit;
?>
