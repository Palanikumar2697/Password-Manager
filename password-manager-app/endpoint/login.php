<?php
session_start();

include('../conn/conn.php');
include('../endpoint/modal_helper.php');

$loginPage = "../index.php";
$homePage  = "../home.php";

/* ---------------- REQUEST METHOD ---------------- */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: $loginPage");
    exit;
}

/* ---------------- INPUT ---------------- */
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    showModal1(
        "Input Required",
        "⚠️ Username and Password cannot be empty!",
        "warning",
        $loginPage
    );
    exit;
}

/* ---------------- FETCH USER ---------------- */
$stmt = $conn->prepare("
    SELECT tbl_user_id, password, last_login
    FROM tbl_user
    WHERE username = :username
    LIMIT 1
");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    showModal1(
        "User Not Found",
        "⚠️ No account found with that username.",
        "warning",
        $loginPage
    );
    exit;
}

/* ---------------- PASSWORD VERIFY ---------------- */
if (!password_verify($password, $user['password'])) {
    showModal1(
        "Login Failed",
        "❌ Incorrect Password!",
        "danger",
        $loginPage
    );
    exit;
}

/* ---------------- LOGIN SUCCESS ---------------- */
session_regenerate_id(true);

/* Store PREVIOUS login */
$_SESSION['previous_login'] = $user['last_login'];

$_SESSION['user_id']       = (int) $user['tbl_user_id'];
$_SESSION['username']      = $username;
$_SESSION['login_time']    = time();
$_SESSION['last_activity'] = time();
$_SESSION['flash_status']  = "success";
$_SESSION['flash_msg']     = "✅ Login Successfully!";

/* ---------------- UPDATE LAST LOGIN ---------------- */
$update = $conn->prepare("
    UPDATE tbl_user
    SET last_login = NOW()
    WHERE tbl_user_id = :id
");
$update->execute(['id' => $user['tbl_user_id']]);

header("Location: $homePage");
exit;
