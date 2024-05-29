<?php
// Encryption key (should be kept secure)
$encryption_key = "your_secret_key";

// Function to encrypt data
function encrypt_data($data, $key) {
    $cipher_algo = "AES-256-CBC";
    $iv_length = openssl_cipher_iv_length($cipher_algo);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($data, $cipher_algo, $key, 0, $iv);
    return base64_encode($encrypted . "::" . $iv);
}

// Function to decrypt data
function decrypt_data($data, $key) {
    $cipher_algo = "AES-256-CBC";
    list($encrypted_data, $iv) = explode("::", base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, $cipher_algo, $key, 0, $iv);
}

// Example usage
$question = "Evaluate the definite integral "\\"( "\\"int_{0}^{1} x^2 dx "\\")."; // The question you want to encrypt
$encrypted_question = encrypt_data($question, $encryption_key);
echo "Encrypted Question: " . $encrypted_question . "<br>";

$decrypted_question = decrypt_data($encrypted_question, $encryption_key);
echo "Decrypted Question: " . $decrypted_question;
?>