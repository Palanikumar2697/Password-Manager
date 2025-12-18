<?php
session_start();
$_SESSION['last_activity'] = time();
http_response_code(200);
?>