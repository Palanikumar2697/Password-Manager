<?php
session_start();

include('../conn/conn.php');
include('../endpoint/modal_helper.php');

/* ---------------- HELPER FUNCTIONS ---------------- */
function setFieldError($field, $message) {
    $_SESSION['errors'][$field] = $message;
}

function keepFormData() {
    $_SESSION['form_data'] = $_POST;
}

$redirectSuccess = "../index.php";

/* ---------------- REQUEST METHOD CHECK ---------------- */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    keepFormData();
    $_SESSION['show_registration'] = true;
    setFieldError('general', 'Invalid request method.');
    showModal("Error", "❌ Invalid request!", "danger");
    exit;
}

/* ---------------- INPUT ---------------- */
$name            = trim($_POST['name'] ?? '');
$phoneNumber     = trim($_POST['phone_number'] ?? '');
$emailAddress    = trim($_POST['email_address'] ?? '');
$username        = trim($_POST['username'] ?? '');
$password        = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirmpassword'] ?? '');

$_SESSION['show_registration'] = true;

/* ---------------- VALIDATION ---------------- */
if (!preg_match("/^[\p{L}\s]{2,50}$/u", $name)) {
    keepFormData();
    setFieldError('name', 'Name must contain only letters and spaces (2–50 chars).');
    showModal("Warning", "⚠️ Invalid name format.", "warning");
    exit;
}

if (!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
    keepFormData();
    setFieldError('phone_number', 'Enter a valid 10-digit phone number.');
    showModal("Warning", "⚠️ Invalid mobile number.", "warning");
    exit;
}

if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
    keepFormData();
    setFieldError('email_address', 'Invalid email address.');
    showModal("Warning", "⚠️ Invalid email address.", "warning");
    exit;
}

if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $username)) {
    keepFormData();
    setFieldError('username', 'Username must be at least 5 characters.');
    showModal("Warning", "⚠️ Invalid username.", "warning");
    exit;
}

if (strlen($password) < 8) {
    keepFormData();
    setFieldError('password', 'Password must be at least 8 characters.');
    showModal("Warning", "⚠️ Password too short.", "warning");
    exit;
}

if ($password !== $confirmPassword) {
    keepFormData();
    setFieldError('confirmpassword', 'Passwords do not match.');
    showModal("Warning", "⚠️ Passwords do not match.", "warning");
    exit;
}

/* ---------------- DUPLICATE CHECKS ---------------- */
try {
    // Username check
    $stmt = $conn->prepare("SELECT 1 FROM tbl_user WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()) {
        keepFormData();
        setFieldError('username', 'Username already exists.');
        showModal("Warning", "⚠️ Username already exists!", "warning");
        exit;
    }

    // Email check
    $stmt = $conn->prepare("SELECT 1 FROM tbl_user WHERE email_address = :email");
    $stmt->execute(['email' => $emailAddress]);
    if ($stmt->fetch()) {
        keepFormData();
        setFieldError('email_address', 'Email already registered.');
        showModal("Warning", "⚠️ Email already registered!", "warning");
        exit;
    }

    /* ---------------- INSERT USER ---------------- */
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insert = $conn->prepare("
        INSERT INTO tbl_user (name, phone_number, email_address, username, password)
        VALUES (:name, :phone, :email, :username, :password)
    ");

    $insert->execute([
        'name'     => $name,
        'phone'    => $phoneNumber,
        'email'    => $emailAddress,
        'username' => $username,
        'password' => $hashedPassword
    ]);

    unset($_SESSION['form_data'], $_SESSION['errors'], $_SESSION['show_registration']);

    showModal("Success", "✅ User Registered Successfully!", "success", $redirectSuccess);
    exit;

} catch (PDOException $e) {
    keepFormData();
    setFieldError('general', 'Server error. Please try again later.');
    error_log("Registration Error: " . $e->getMessage());
    showModal("Error", "❌ Something went wrong.", "danger");
    exit;
}
