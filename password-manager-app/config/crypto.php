<?php
// 32-byte secret key (KEEP THIS SAFE)
define('SECRET_KEY', 'ChangeThisToYourVeryStrong32CharKey!');

// 16-byte IV
define('SECRET_IV', '16CharInitVector');

// Encrypt function
function encryptPassword($plainText) {
    return openssl_encrypt(
        $plainText,
        'AES-256-CBC',
        SECRET_KEY,
        0,
        SECRET_IV
    );
}

// Decrypt function
function decryptPassword($encryptedText) {
    return openssl_decrypt(
        $encryptedText,
        'AES-256-CBC',
        SECRET_KEY,
        0,
        SECRET_IV
    );
}
