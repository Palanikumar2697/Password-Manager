<?php
session_start();
include('../conn/conn.php');
include('../endpoint/modal_helper.php');

$loginPage = "http://localhost/PM/password-manager-app/index.php";
$homePage = "../home.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Only handle POST requests
    header("Location: $loginPage");
    exit;
}

// Trim inputs
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// 1️⃣ Validate empty inputs
if (empty($username) || empty($password)) {
    showModal1(
        "Input Required",
        "⚠️ Username and Password cannot be empty!",
        "warning",
        $loginPage
    );
    exit;
}

// 2️⃣ Fetch user
$stmt = $conn->prepare("SELECT `tbl_user_id`, `password` FROM `tbl_user` WHERE `username` = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 3️⃣ Handle login validation
if (!$user) {
    // User not found
    showModal1(
        "User Not Found",
        "⚠️ No account found with that username.",
        "warning",
        $loginPage
    );
    exit;
}

if (!password_verify($password, $user['password'])) {
    // Wrong password
    showModal1(
        "Login Failed",
        "❌ Incorrect Password!",
        "danger",
        $loginPage
    );
    exit;
}

// 4️⃣ Login success
$_SESSION['user_id'] = $user['tbl_user_id'];
$_SESSION['username'] = $username;
$_SESSION['flash_status'] = "success";
$_SESSION['flash_msg'] = "✅ Login Successfully!";

header("Location: $homePage");
exit;
?>
