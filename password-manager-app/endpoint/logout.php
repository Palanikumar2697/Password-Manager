<?php
session_start();

/* ---------------- CLEAR SESSION DATA ---------------- */
$_SESSION = [];

/* ---------------- DELETE SESSION COOKIE ---------------- */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* ---------------- DESTROY SESSION ---------------- */
session_destroy();

/* ---------------- START NEW SESSION FOR FLASH MESSAGE ---------------- */
session_start();

$_SESSION['modal'] = [
    'type'    => 'success',
    'title'   => 'Logged Out',
    'message' => 'You have been logged out successfully!'
];

/* ---------------- REDIRECT ---------------- */
header("Location: ../index.php");
exit;
