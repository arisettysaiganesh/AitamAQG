<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "paper"; // Replace with your actual database name
include_once 'bloomsdetect.php';
include_once 'encrypt.php';

$encryption_key = "AITAM2024";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    // Get form data using prepared statements
    $question_id = $_POST["question_id"];
    $unit = $_POST["unit"];
    $marks = $_POST["marks"]; // Default marks for MCQ
    $repetition = ""; // No repetition by default
    $imagepath = $_POST["questionimage"];
    $question = $_POST["question"];
    $blooms_level = isset($_POST["level"]) ? implode(",", $_POST["level"]) : "";
    $subject_id = $_POST["subject"];
    $difficulty = $_POST["difficulty"];
    $regulation_id = $_POST["regulation"];

    // Escape values to prevent SQL injection
    $question_id = $conn->real_escape_string($question_id);
    $unit = $conn->real_escape_string($unit);
    $imagepath = $conn->real_escape_string($imagepath);
    $question = $conn->real_escape_string($question);
    $question1=$question;
    $blooms_level = $conn->real_escape_string($blooms_level);
    $subject_id = $conn->real_escape_string($subject_id);
    $difficulty = $conn->real_escape_string($difficulty);
    $regulation_id = $conn->real_escape_string($regulation_id);
    $questiont=$question;
    $blooms_level= detectBloomsLevels($questiont);
    
    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO question (question_id, unit_id, marks, repetition, imagepath, question, blooms_level, subject_id, difficulty, regulation_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $encryption_key = "AITAM2024";
    $question1=encrypt_data($question1, $encryption_key);

    // Bind parameters
    $stmt->bind_param("ssississss", $question_id, $unit, $marks, $repetition, $imagepath, $question, $blooms_level, $subject_id, $difficulty, $regulation_id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Fetch data for the engineering branches
$sqlEngineeringBranch = "SELECT DISTINCT college_name FROM college";
$resultEngineeringBranch = $conn->query($sqlEngineeringBranch);

// Fetch data for the subjects
$sqlSubjects = "SELECT subject_id, subject_name FROM subjects";
$resultSubjects = $conn->query($sqlSubjects);

// Fetch data for the regulations
$sqlRegulations = "SELECT DISTINCT regulation_name FROM regulations";
$resultRegulations = $conn->query($sqlRegulations);

// Fetch data for the units
$sqlUnits = "SELECT unit_id,unit_value FROM unit";
$resultUnits = $conn->query($sqlUnits);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insert Questions</title>
</head>
<body>
  <div class="exam-block">
    <h2>Insert Questions</h2>
    <form action="#" method="post">
      <label for="question_id">Question_ID:</label>
      <input type="text" id="question_id" name="question_id" placeholder="Enter question ID">
      <label for="unit">Unit:</label>
      <select id="unit" name="unit">
        <?php
        // Generate options for units
        while ($row = $resultUnits->fetch_assoc()) {
            echo "<option value='{$row['unit_id']}'>{$row['unit_value']}</option>";
        }
        ?>
      </select>
      <label for="question">Question:</label>
      <input type="text" id="question" name="question" placeholder="Enter question">
      <label for="questionimage">Image File Path:</label>
      <input type="text" id="questionimage" name="questionimage" placeholder="Enter Path of the image">
      <label for="marks">marks:</label>
      <input type="text" id="marks" name="marks" placeholder="Enter marks">
      <div class="difficulty-block">
        <label for="difficulty">Difficulty Level</label>
        <select id="difficulty" name="difficulty">
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
      </div>
      <label for="subject">Subject:</label>
      <select id="subject" name="subject">
        <?php
        // Generate options for subjects
        while ($row = $resultSubjects->fetch_assoc()) {
            echo "<option value='{$row['subject_id']}'>{$row['subject_name']}</option>";
        }
        ?>
      </select>
      <label for="regulation">Regulation:</label>
      <select id="regulation" name="regulation">
        <?php
        // Generate options for regulations
        while ($row = $resultRegulations->fetch_assoc()) {
            echo "<option value='{$row['regulation_name']}'>{$row['regulation_name']}</option>";
        }
        ?>
      </select>
      <h2>Bloom's Taxonomy levels</h2>
      <div class="checkbox-container">
        <!-- Checkbox options for Bloom's levels -->
      </div>    
      <input type="submit" name="submit" value="Submit">
    </form>
  </div>
</body>
</html>
<?php
// Close result sets
$resultEngineeringBranch->free_result();
$resultSubjects->free_result();
$resultRegulations->free_result();
$resultUnits->free_result();

// Close the database connection
$conn->close();
?>
