<?php
// Encryption key (should be kept secure)
$encryption_key = "AITAM2024";

// Function to encrypt data
function encrypt_data($data, $encryption_key) {
    $cipher_algo = "AES-256-CBC";
    $iv_length = openssl_cipher_iv_length($cipher_algo);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($data, $cipher_algo, $encryption_key, 0, $iv);
    return base64_encode($encrypted . "::" . $iv);
}

// Function to decrypt data
function decrypt_data($data, $encryption_key) {
    $cipher_algo = "AES-256-CBC";
    list($encrypted_data, $iv) = explode("::", base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, $cipher_algo, $encryption_key, 0, $iv);
}

?>