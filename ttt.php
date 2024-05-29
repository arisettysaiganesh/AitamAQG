<?php
$key = "AITAM2024";

function encrypt_data($data, $key) {
    $cipher_algo = "AES-256-CBC";
    $iv_length = openssl_cipher_iv_length($cipher_algo);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($data, $cipher_algo, $key, 0, $iv);
    return base64_encode($encrypted . "::" . $iv);
}

// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$database = "paper";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all rows from the upload table
// $sql = "SELECT * FROM question";
$sql = "SELECT * FROM question WHERE subject_id = '20DSHT107'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each row
    while($row = $result->fetch_assoc()) {
        // Encrypt the question
        $encrypted_question = encrypt_data($row["question"], $key);
        
        // Update the row with the encrypted question
        $update_sql = "UPDATE question SET question = '" . $encrypted_question . "' WHERE question_id = '" . $row["question_id"] . "'";
        if ($conn->query($update_sql) === TRUE) {
            echo "Question with ID " . $row["question_id"] . " encrypted successfully.<br>";
        } else {
            echo "Error updating question: " . $conn->error;
        }
    }
} else {
    echo "0 results";
}

// Close the database connection
$conn->close();
?>
