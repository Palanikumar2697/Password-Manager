
<?php
session_start();
$timeout = 600;

if (isset($_SESSION['expires_at'])) {
    $_SESSION['expires_at'] = time() + $timeout;
}

http_response_code(204);
exit;
