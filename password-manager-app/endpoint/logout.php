<?php
session_start();
require_once('../endpoint/modal_helper.php'); // only if you use modal_helper, else remove

// Clear all session variables
$_SESSION = [];

// Destroy the session completely
session_destroy();

// Start a new session to store the modal message
session_start();
$_SESSION['modal'] = [
    "type" => "success",
    "title" => "Logged Out",
    "message" => "You have been logged out successfully!"
];

// Redirect back to login page (index.php)
header("Location: ../index.php");
exit;
