<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "paper"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$encryption_key = "AITAM2024";
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data using prepared statements
    $question_id = $_POST["question_id"];
    $unit_id = $_POST["unit"];
    $marks = $_POST["marks"]; // Assuming marks value is selected from the form
    $repetition = $_POST["repetition"]; // Assuming repetition value is selected from the form
    $imagepath = $_POST["questionimage"];
    $question = $_POST["question"];
    $questionText=$question;
     // Convert array to a comma-separated string
    $subject_id = $_POST["subject"];
    $difficulty = $_POST["difficulty"];

    // Escape values to prevent SQL injection
    $question_id = $conn->real_escape_string($question_id);
    $unit_id = $conn->real_escape_string($unit_id);
    $imagepath = $conn->real_escape_string($imagepath);
    $question = $conn->real_escape_string($question);
    $blooms_level=detectBloomsLevels($questionText);
    $blooms_level = $conn->real_escape_string($blooms_level);
    $subject_id = $conn->real_escape_string($subject_id);
    $difficulty = $conn->real_escape_string($difficulty);

    
$encrypted_question = encrypt_data($question, $encryption_key);

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO question (question_id, unit_id, marks, repetition, imagepath, question, blooms_level, subject_id, difficulty)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssissssss", $question_id, $unit_id, $marks, $repetition, $imagepath, $encrypted_question, $blooms_level, $subject_id, $difficulty);

    // Execute the query
    if ($stmt->execute()) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Fetch data for the subjects
$sqlSubjects = "SELECT subject_id, subject_name FROM subjects";
$resultSubjects = $conn->query($sqlSubjects);

// Fetch data for the units
$sqlUnits = "SELECT unit_id, unit_value FROM unit";
$resultUnits = $conn->query($sqlUnits);

// Fetch data for the engineering branches
$sqlEngineeringBranch = "SELECT DISTINCT college_name FROM college";
$resultEngineeringBranch = $conn->query($sqlEngineeringBranch);


// Fetch data for the regulations
$sqlRegulations = "SELECT DISTINCT regulation_name FROM regulations";
$resultRegulations = $conn->query($sqlRegulations);

function detectBloomsLevels($questionText) {
  $bloomsKeywords = array(
      'l1' => array('recall', 'define', 'identify', 'recognize', 'choose', 'describe', 'is', 'are', 'where', 'which', 'who was', 'why did', 'what is', 'what are', 'when did', 'outline', 'list', 'remember', 'describe','What is','How is','Where is','When did','How did',
  'How would you explain',
  'How would you describe',
  'What do you recall',
  'How would you show',
  'Who (what) were the main',
  'What are three',
  'What is the definition of'),
      'l2' => array('compare', 'contrast', 'clarify', 'differentiate between', 'generalize', 'express', 'infer', 'observe', 'identify', 'describe', 'restate', 'elaborate on', 'what if', 'what is the main idea', 'what can you say about','How would you classify the type of',
  'How would you compare',
  'contrast',
  'How would you rephrase the meaning',
  'What facts or ideas show',
  'What is the main idea of',
  'Which statements support',
  'How can you explain what is meant',
  'What can you say about',
  'Which is the best answer',
  'How would you summarize'),
      'l3' => array('demonstrate', 'present', 'change', 'modify', 'solve', 'use', 'predict', 'construct', 'perform', 'classify', 'apply','How would you use',
  'What examples can you find to',
  'How would you solve',
  'using what you have learned',
  'How would you organize',
  'to show',
  'How would you show your understanding of',
  'What approach would you use to',
  'How would you apply what you learned to develop',
  'What other way would you plan to',
  'What would result if',
  'How can you make use of the facts to',
  'What elements would you choose to change',
  'What facts would you select to show',
  'What questions would you ask'),
      'l4' => array('sort', 'analyze', 'identify', 'examine', 'investigate', 'what', 'assumptions', 'validate', 'ideas', 'explain', 'what is the main idea','What are the parts or features of',
  'How is',
  'related to',
  'Why do you think',
  'What is the theme',
  'What motive is there',
  'What conclusions can you draw',
  'How would you classify',
  'How can you identify the different parts',
  'What evidence can you find',
  'What is the relationship between',
  'How can you make a distinction between',
  'What is the function of',
  'What ideas justify'),
      'l5' => array('evaluate', 'find', 'select', 'decide', 'justify', 'debate', 'judge', 'criteria', 'verify', 'information', 'prioritize',
      'Why do you agree with the actions? The outcomes?',
  'What is your opinion of',
  '(Must explain why)',
  'How would you prove',
  'disprove',
  'How can you assess the value or importance of',
  'What would you recommend',
  'How would you rate or evaluate the',
  'What choice would you have made',
  'How would you prioritize',
  'What details would you use to support the view',
  'Why was it better than'
      ),
      'l6' => array('suggest', 'revise', 'explain the reason', 'generate a plan', 'invent', 'gather facts', 'predict the outcome', 'portray', 'devise a way', 'compile facts', 'elaborate on the reason', 'improve', 'What changes would you make to solve',
  'How would you improve',
  'What would happen if',
  'How can you elaborate on the reason',
  'What alternative can you propose',
  'How can you invent',
  'How would you adapt',
  'to create a different',
  'How could you change',
  'What could be done to minimize',
  'What way would you design',
  'What could be combined to improve',
  'How would you test or formulate a theory for',
  'What would you predict as the outcome',
  'How can a model be constructed that would change',
  'What is an original way for the')
  );

  $detectedLevels = array();

  foreach ($bloomsKeywords as $level => $keywords) {
      foreach ($keywords as $keyword) {
          if (stripos($questionText, $keyword) !== false) {
              $detectedLevels[] = $level;
              break;
          }
      }
  }

  return implode(',', $detectedLevels);
}

// Example usage


// Now $detectedBloomsLevels contains the detected Bloom's levels, you can use it when inserting into the database.






// Function to encrypt data
function encrypt_data($data, $key) {
    $cipher_algo = "AES-256-CBC";
    $iv_length = openssl_cipher_iv_length($cipher_algo);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($data, $cipher_algo, $key, 0, $iv);
    return base64_encode($encrypted . "::" . $iv);
}

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
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

  <div class="exam-block">
    <h2>Insert Questions</h2>
    <form action="#" method="post">

    <label for="question_id">Question ID:</label>
      <input type="text" id="question_id" name="question_id" placeholder="Enter question ID">

      <!-- Other form fields -->
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
      <label for="repetition">Repetition (Percentage):</label>
      <input type="text" id="repetition" name="repetition" placeholder="Enter repetition percentage">

      <label for="question">Question:</label>
      <input type="text" id="question" name="question" placeholder="Enter question">

      <label for="regulation">Regulation:</label>
      <select id="regulation" name="regulation">
        <?php
        // Generate options for regulations
        while ($row = $resultRegulations->fetch_assoc()) {
            echo "<option value='{$row['regulation_name']}'>{$row['regulation_name']}</option>";
        }
        ?>
      </select>


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
      <!-- Additional form fields -->
  
      <div class="difficulty-block">
        <label for="difficulty">Difficulty Level</label>
        <select id="difficulty" name="difficulty">
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
      </div>

      <label for="marks">Marks:</label>
      <select id="marks" name="marks">
        <option value="5">5</option>
        <option value="10">10</option>
      </select>

      

      <input type="submit" name="submit" value="Submit">
    </form>
  </div>

</body>
<?php
// Close result sets
$resultSubjects->free_result();
$resultUnits->free_result();

// Close the database connection
$conn->close();
?>
</html>
