<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "paper"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if file is uploaded
if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $file = $_FILES["file"]["tmp_name"];

    // Load data from CSV file into the question table
    $sql = "LOAD DATA INFILE '$file'
            INTO TABLE question
            FIELDS TERMINATED BY ',' ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (question_id, unit_id, marks, repetition, imagepath, question, blooms_level, subject_id, difficulty, regulation_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Questions uploaded successfully";
    } else {
        echo "Error uploading questions: " . $conn->error;
    }
} else {
    echo "Error uploading file";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Questions</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
}

input[type="file"] {
    margin-bottom: 20px;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

        </style>
</head>
<body>
    <div class="container">
        <h2>Upload Questions CSV</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" accept=".csv" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
