<?php
function showModal1($title, $message, $type, $redirect) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['modal'] = [
        'title' => $title,
        'message' => $message,
        'type' => $type
        
    ];
    header("Location: $redirect");
    exit;
}



function showModal($title, $message, $type, $redirect = "../index.php?show=register") {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['modal'] = [
        'title'   => $title,
        'message' => $message,
        'type'    => $type
    ];

    header("Location: $redirect");
    exit();
}


?>



