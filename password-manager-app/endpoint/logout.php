<?php
session_start();

$_SESSION['modal'] = [
    'type' => 'success',
    'message' => 'You have been logged out successfully.'
];

// Optional: unset auth only
unset($_SESSION['user_id']);
unset($_SESSION['username']);

header("Location: ../index.php");
exit;
