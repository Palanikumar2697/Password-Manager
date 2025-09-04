<?php
include('../conn/conn.php');

if (isset($_GET['id'])) {
    $userId = (int) $_GET['id'];

    $stmt = $conn->prepare("SELECT tbl_user_id, name, phone_number, email_address, username, password 
                            FROM tbl_user 
                            WHERE tbl_user_id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(null);
    }
}
?>
