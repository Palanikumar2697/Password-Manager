<?php
session_start();
include('../conn/conn.php');               // your PDO $conn
include('../endpoint/modal_helper.php');  // provides showModal(), maybe keepFormData() in your project

// --------------------
// Helper functions
// --------------------
function setFieldError($field, $message) {
    if (!isset($_SESSION['errors'])) $_SESSION['errors'] = [];
    $_SESSION['errors'][$field] = $message;
}

function keepFormData() {
    $_SESSION['form_data'] = $_POST;
}

// --------------------
// Redirect after success (used if showModal accepts redirect)
$redirectSuccess = "../index.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // invalid access
    keepFormData();
    $_SESSION['show_registration'] = true;
    setFieldError('general', 'Invalid request method.');
    showModal("Error", "❌ Invalid request!", "danger");
    exit();
}

// Trim inputs
$name            = trim($_POST['name'] ?? '');
$phoneNumber     = trim($_POST['phone_number'] ?? '');
$emailAddress    = trim($_POST['email_address'] ?? '');
$username        = trim($_POST['username'] ?? '');
$password        = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirmpassword'] ?? '');
$created_by = $_SESSION['user_id'];

// make sure registration form opens on redirect
$_SESSION['show_registration'] = true;

// --------------------
// VALIDATION (set field errors & show modal)
// --------------------

// Name: Unicode letters + spaces, 2–50 chars
if (!preg_match("/^[\p{L}\s]{2,50}$/u", $name)) {
    keepFormData();
    setFieldError('name', 'Name must contain only letters and spaces (2–50 chars).');
    showModal("Warning", "⚠️ Name must contain only letters and spaces!", "warning");
    exit();
}

// Phone number: exactly 10 digits
if (!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
    keepFormData();
    setFieldError('phone_number', 'Enter a valid 10-digit phone number.');
    showModal("Warning", "⚠️ Invalid Mobile Number!", "warning");
    exit();
}

// Email required
if (empty($emailAddress)) {
    keepFormData();
    setFieldError('email_address', 'Email address is required.');
    showModal("Warning", "⚠️ Email address is required!", "warning");
    exit();
}

// Email format
if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
    keepFormData();
    setFieldError('email_address', 'Invalid email format.');
    showModal("Warning", "⚠️ Invalid Email Address!", "warning");
    exit();
}

// Username: letters & numbers only, min 5 chars
if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $username)) {
    keepFormData();
    setFieldError('username', 'Minimum 5 characters, letters & numbers only.');
    showModal("Warning", "⚠️ Username must be at least 5 characters and contain only letters & numbers!", "warning");
    exit();
}

// Password min length
if (strlen($password) < 8) {
    keepFormData();
    setFieldError('password', 'Password must be at least 8 characters.');
    showModal("Warning", "⚠️ Password must be at least 8 characters long!", "warning");
    exit();
}

// Confirm password
if ($password !== $confirmPassword) {
    keepFormData();
    setFieldError('confirmpassword', 'Passwords do not match.');
    showModal("Warning", "⚠️ Password and Confirm Password do not match!", "warning");
    exit();
}

// --------------------
// DUPLICATE CHECKS & INSERT
// --------------------
try {
    $stmt = $conn->prepare("SELECT username, email_address FROM tbl_user WHERE username = :username OR email_address = :email LIMIT 1");
    $stmt->execute(['username' => $username, 'email' => $emailAddress]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        keepFormData();
        if (strcasecmp($row['username'], $username) === 0) {
            setFieldError('username', 'This username is already taken.');
            showModal("Warning", "⚠️ Username already exists!", "warning");
            exit();
        } else {
            setFieldError('email_address', 'This email is already registered.');
            showModal("Warning", "⚠️ Email already registered!", "warning");
            exit();
        }
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
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

    // Clear any form data and errors on success
    unset($_SESSION['form_data']);
    unset($_SESSION['errors']);
    unset($_SESSION['show_registration']);

    // Success (if showModal supports redirect param)
    showModal("Success", "✅ User Registered Successfully!", "success", $redirectSuccess);
    exit();

} catch (PDOException $e) {
    // keep user inputs so they can correct
    keepFormData();
    setFieldError('general', 'A server error occurred. Please try again later.');
    error_log("Registration DB Error: " . $e->getMessage());
    showModal("Error", "❌ Something went wrong. Please try again later.", "danger");
    exit();
}
