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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    // Get form data using prepared statements
    $engineeringBranch = $_POST["engineeringBranch"];
    $subject = $_POST["subject"];
    $regulation = $_POST["regulation"];
    $question = $_POST["question"];
    $question_id = $_POST["question_id"];
    $option_a=$_POST["option_a"];
    $option_b=$_POST["option_b"];
    $option_c=$_POST["option_c"];
    $option_d=$_POST["option_d"];
    $unit = $_POST["unit"];
    $levels = isset($_POST["level"]) ? $_POST["level"] : [];
    $questionimage = $_POST["questionimage"];
    $coLevel = $_POST["coLevel"];
    $difficulty = $_POST["difficulty"];
    $imagePath = $_POST["questionimage"];
    
    // Convert array to a comma-separated string for database storage
    $bloomLevels = implode(", ", $levels);

    // Escape values to prevent SQL injection
    $engineeringBranch = $conn->real_escape_string($engineeringBranch);
    $subject = $conn->real_escape_string($subject);
    $regulation = $conn->real_escape_string($regulation);
    $question = $conn->real_escape_string($question);
    $question_id = $conn->real_escape_string($question_id);
    $option_a = $conn->real_escape_string($option_a);
    $option_b = $conn->real_escape_string($option_b);
    $option_c = $conn->real_escape_string($option_c);
    $option_d = $conn->real_escape_string($option_d);
    $unit = $conn->real_escape_string($unit);
    $levels = $conn->real_escape_string($bloomLevels); // Fix this line to use $bloomLevels
    $questionimage = $conn->real_escape_string($questionimage);
    $coLevel = $conn->real_escape_string($coLevel);
    $difficulty = $conn->real_escape_string($difficulty);
    $imagePath = $conn->real_escape_string($imagePath);

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO mcq (mcq_id,subject_id, unit_id, question, option_a, option_b, option_c, option_d, imagepath, blooms_level, repetition)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssssssssss",$question_id, $subject, $unit, $question, $option_a, $option_b, $option_c, $option_d, $imagePath, $bloomLevels, $repetition);

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
  <style>
    .exam-block {
      max-width: 600px;
      margin: auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
      display: block;
      margin-top: 10px;
    }

    select,
    input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .co-level-block,
    .difficulty-block {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
    }

    .checkbox-container {
      display: flex;
      flex-wrap: wrap;
    }

    .checkbox-column {
      width: 33.33%;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <div class="exam-block">
    <h2>Insert Questions</h2>
    <form action="#" method="post">

    <label for="question_id">Question_ID:</label>
      <input type="text" id="question_id" name="question_id" placeholder="Enter question ID">

      <label for="option_a">option_a:</label>
      <input type="text" id="option_a" name="option_a" placeholder="Enter option_a ">
      
      <label for="option_b">option_b:</label>
      <input type="text" id="option_b" name="option_b" placeholder="Enter option_b ">

      <label for="option_c">option_c:</label>
      <input type="text" id="option_c" name="option_c" placeholder="Enter option_c ">

      <label for="option_d">option_d:</label>
      <input type="text" id="option_d" name="option_d" placeholder="Enter option_d ">

      <label for="engineeringBranch">Select Engineering Branch:</label>
      <select id="engineeringBranch" name="engineeringBranch">
        <?php
        // Generate options for engineering branches
        while ($row = $resultEngineeringBranch->fetch_assoc()) {
            echo "<option value='{$row['college_name']}'>{$row['college_name']}</option>";
        }
        ?>
      </select>

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


      <label for="question">Question:</label>
      <input type="text" id="question" name="question" placeholder="Enter question">

      

      
      <label for="unit">Unit:</label>
      <select id="unit" name="unit">
        <?php
        // Generate options for units
        while ($row = $resultUnits->fetch_assoc()) {
            echo "<option value='{$row['unit_id']}'>{$row['unit_value']}</option>";
        }
        ?>
      </select>

      <label for="questionimage">Image File Path:</label>
      <input type="text" id="questionimage" name="questionimage" placeholder="Enter Path of the image">

      <div class="co-level-block">
        <label for="coLevel">CO Level</label>
        <select id="coLevel" name="coLevel">
          <option value="co1">CO1</option>
          <option value="co2">CO2</option>
          <option value="co3">CO3</option>
          <option value="co4">CO4</option>
          <option value="co5">CO5</option>
          <option value="co6">CO6</option>
        </select>
      </div>
      
      <div class="difficulty-block">
        <label for="difficulty">Difficulty Level</label>
        <select id="difficulty" name="difficulty">
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
      </div>

      <h2>Bloom's Taxonomy levels</h2>
      <div class="checkbox-container">
        <div class="checkbox-column">
          <label for="remember">
            <input type="checkbox" id="remember" name="level[]" value="l1"> Remember
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="understand">
            <input type="checkbox" id="understand" name="level[]" value="l2"> Understand
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="apply">
            <input type="checkbox" id="apply" name="level[]" value="l3"> Apply
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="analyze">
            <input type="checkbox" id="analyze" name="level[]" value="l4"> Analyze
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="evaluate">
            <input type="checkbox" id="evaluate" name="level[]" value="l5"> Evaluate
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="create">
            <input type="checkbox" id="create" name="level[]" value="l6"> Create
          </label>
        </div>
      </div>    
      <input type="submit" name="submit" value="Submit">
    </form>
  </div>

</body>
<?php
// Close result sets
$resultEngineeringBranch->free_result();
$resultSubjects->free_result();
$resultRegulations->free_result();
$resultUnits->free_result();

// Close the database connection
$conn->close();
?>
</html>
