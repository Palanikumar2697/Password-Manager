<?php
include('../conn/conn.php');

// Fetch all users
$stmt = $conn->query("SELECT tbl_user_id, password FROM tbl_user");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    // If already hashed, skip
    if (password_get_info($row['password'])['algo'] !== 0) {
        continue;
    }

    // Hash plain-text password
    $hashed = password_hash($row['password'], PASSWORD_DEFAULT);

    // Update DB
    $update = $conn->prepare("
        UPDATE tbl_user 
        SET password = :password 
        WHERE tbl_user_id = :id
    ");

    $update->execute([
        ':password' => $hashed,
        ':id'       => $row['tbl_user_id']
    ]);
}

echo "✅ All plain-text passwords converted to hashed passwords.";
