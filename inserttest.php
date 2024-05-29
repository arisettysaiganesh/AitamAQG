<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "qp1";

function detectBloomsLevels($questionText) {
    $bloomsKeywords = array(
        'l1' => array('define', ' choose find', 'how', ' recall', 'relate', 'select', 'show', 'spell', 'tell', 'what', 'when', 'where', 'which', 'who', 'why', 'label', 'list', 'match', 'name', 'omit ','recall', 'define', 'identify', 'recognize', 'choose', 'describe', 'is', 'are', 'where', 'which', 'who was', 'why did', 'what is', 'what are', 'when did', 'outline', 'list', 'remember', 'describe','What is','How is','Where is','When did','How did',
    'How would you explain',
    'How would you describe',
    'What do you recall',
    'How would you show',
    'Who (what) were the main',
    'What are three',
    'What is the definition of'),
    //Describe the process of photosynthesis
        'l2' => array('extend', 'illustrate', 'infer', 'interpret', 'outline', 'relate', 'rephrase', 'show', 'summarize', 'translate', 'classify', 'compare', 'contrast', 'demonstrate', 'explain ','compare', 'contrast', 'clarify', 'differentiate between', 'generalize', 'express', 'infer', 'observe', 'identify', 'restate', 'elaborate on', 'what if', 'what is the main idea', 'what can you say about','How would you classify the type of',
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
        'l3' => array('apply', 'build', 'choose', 'construct', 'develop', 'experimentwith', 'identify', 'interview', 'makeuseof', 'model', 'organize', 'plan', 'select', 'solve', 'utilize','demonstrate', 'present', 'change', 'modify', 'solve', 'use', 'predict', 'construct', 'perform', 'classify', 'apply','How would you use',
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
        'l4' => array('analyze', 'assume', 'categorize', 'classify', 'compare', 'conclusion', 'contrast', 'discover', 'dissect', 'distinguish', 'divide', 'examine', 'function', 'inference', 'inspect', 'list', 'motive', 'relationships', 'simplify', 'survey', 'takepartin', 'testfor', 'theme','sort', 'analyze', 'identify', 'examine', 'investigate', 'what', 'assumptions', 'validate', 'ideas', 'explain', 'what is the main idea','What are the parts or features of',
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
        'l5' => array('agree', 'appraise', 'assess', 'award', 'choose', 'compare', 'conclude', 'criteria', 'criticize', 'decide', 'deuct', 'defend', 'determine', 'disprove', 'estimate', 'evaluate', 'explain', 'importance', 'influence', 'interpret', 'judge', 'justify', 'mark', 'measure', 'opinion', 'perceive', 'prioritize', 'prove', 'rate', 'recommend', 'ruleon', 'select', 'support', 'value','evaluate', 'find', 'select', 'decide', 'justify', 'debate', 'judge', 'criteria', 'verify', 'information', 'prioritize',
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
        'l6' => array('adapt', 'build', 'change', 'combine', 'compile', 'compose', 'construct', 'create', 'delete', 'design', 'develop', 'discuss', 'elaborate', 'estimate', 'formulate', 'happen', 'imagine', 'improve', 'invent', 'makeup', 'maximize', 'minimize', 'modify', 'original', 'orginate', 'plan', 'predict', 'propose', 'solution', 'solve', 'suppose', 'test', 'theory','suggest', 'revise', 'explain the reason', 'generate a plan', 'invent', 'gather facts', 'predict the outcome', 'portray', 'devise a way', 'compile facts', 'elaborate on the reason', 'improve', 'What changes would you make to solve',
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
$marks = $_POST["marks"];
$unit = $_POST["unit"];
$levels = isset($_POST["level"]) ? implode(", ", $_POST["level"]) : "";
$questionimage = $_POST["questionimage"]; // Corrected key
$coLevel = $_POST["coLevel"];
$difficulty = $_POST["difficulty"];
$imagePath = $_POST["questionimage"]; // Use the same key as above
//$bloomLevels = implode(", ", $_POST["level"]);

// Example usage
$questionText = "Your question text goes here.";
$bloomLevels = detectBloomsLevels($question);

// Now $detectedBloomsLevels contains the detected Bloom's levels, you can use it when inserting into the database.


// Escape values to prevent SQL injection
$engineeringBranch = $conn->real_escape_string($engineeringBranch);
$subject = $conn->real_escape_string($subject);
$regulation = $conn->real_escape_string($regulation);
$question = $conn->real_escape_string($question);
$marks = $conn->real_escape_string($marks);
$unit = $conn->real_escape_string($unit);
$levels = $conn->real_escape_string($levels);
$questionimage = $conn->real_escape_string($questionimage);
$coLevel = $conn->real_escape_string($coLevel);
$difficulty = $conn->real_escape_string($difficulty);
$imagePath = $conn->real_escape_string($imagePath);

// Use prepared statement to prevent SQL injection
$sql = "INSERT INTO importqustions (engineeringBranch, subject, regulation, question, marks, unit, levels, questionimage, coLevel, difficulty, imagepath, bloomLevels) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("ssssississss", $engineeringBranch, $subject, $regulation, $question, $marks, $unit, $levels, $questionimage, $coLevel, $difficulty, $imagePath, $bloomLevels);

// Execute the query
if ($stmt->execute()) {
    echo "Record inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $stmt->error;
}



    // SQL query to insert data into the database
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Question Insertion Original</title>
  <style>

    body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to right, #3498db, #6bb9f0);
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 140vh;
}

.exam-block {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 30%;
    text-align: center;
}

.taxonomy-block {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 300px;
    text-align: left;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #3498db;
    font-size: 18px;
}

select,
input {
    width: 100%;
    padding: 8px;
    margin-bottom: 16px;
    box-sizing: border-box;
    border: 1px solid #3498db;
    border-radius: 5px;
    height:40px;
    font-size: 15px; /* Adjust the font size as needed */
}

input[type="submit"] {
    background-color: #4caf50;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.checkbox-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    
}

.checkbox-column {
    flex: 0 1 calc(33.33% - 10px);
    margin: 5px;
}
button:hover {
    background: linear-gradient(to right, #6bb9f0, #3498db);
}
   
  </style>
</head>

<body>

  <div class="exam-block">
    <h2>Insert Questions</h2>
    <form action="#" method="post">

      <label for="engineeringBranch">Select Engineering Branch:</label>
  <select id="engineeringBranch" name="engineeringBranch">
    <option value="INF">IT204 - Information Technology</option>
    <option value="cse101">CSE101 - Computer Science and Engineering</option>
    <option value="ece102">ECE102 - Electronics and Communication Engineering</option>
    <option value="mech103">MECH103 - Mechanical Engineering</option>
    <option value="civil104">CIVIL104 - Civil Engineering</option>
    <option value="eee105">EEE105 - Electrical and Electronics Engineering</option>
    <!-- Add more engineering branches as needed -->
  </select>

      <label for="subject">Subject:</label>
      <select id="subject" name="subject">
        <option value="20DSHT107">20DSHT107  - Chemistry</option>
        <option value="cse103">cse103 - Database_Management_Systems</option>
        <option value="cse104">cse104 - Computer Networks</option>
        <option value="cse105">cse105 - Operating Systems</option>
        <option value="cse106">cse106 - Software Engineering</option>
        <option value="cse107">cse107 - Artificial Intelligence</option>
        <option value="cse108">cse108 - Machine Learning</option>
        <option value="cse109">cse109 - Web Development</option>
        <option value="cse110">cse110 - Cybersecurity</option>
        <option value="cse111">cse111 - Computer Graphics</option>
        <option value="cse112">cse112 - Computer Organization and Architecture</option>
        <option value="cse113">cse113 - Human-Computer Interaction</option>
        <option value="cse114">cse114 - Data Mining</option>
        <option value="cse115">cse115 - Cloud Computing</option>
        <option value="cse116">cse116 - Distributed Systems</option>
        <option value="cse117">cse117 - Computer Vision</option>
        <option value="cse118">cse118 - Natural Language Processing</option>
        <option value="cse119">cse119 - Robotics</option>
        <option value="cse120">cse120 - Parallel and Concurrent Programming</option>

        <!-- Add more engineering subjects as needed -->
      </select>
      <label for="regulation">Regulation:</label>
      <select id="regulation" name="regulation">
        <option value="AR20">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
      </select>

      <label for="question">Question:</label>
      <input type="text" id="question" name="question" placeholder="Enter question">

      <label for="marks">Marks:</label>
      <select id="marks" name="marks">
      <option value="10">10</option>
        <option value="2">2</option>
        <option value="8">8</option>
        <option value="5">5</option>
        <option value="7">7</option>
        <option value="3">3</option>
        
      </select>

      <label for="unit">Unit:</label>
      <select id="unit" name="unit">
        <option value="U1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
      </select>

      <label for="image">Image File Path:</label>
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
            <input type="checkbox" id="remember" name="level[]" value="remember"> Remember
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="understand">
            <input type="checkbox" id="understand" name="level[]" value="understand"> Understand
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="apply">
            <input type="checkbox" id="apply" name="level[]" value="apply"> Apply
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="analyze">
            <input type="checkbox" id="analyze" name="level[]" value="analyze"> Analyze
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="evaluate">
            <input type="checkbox" id="evaluate" name="level[]" value="evaluate"> Evaluate
          </label>
        </div>
    
        <div class="checkbox-column">
          <label for="create">
            <input type="checkbox" id="create" name="level[]" value="create"> Create
          </label>
        </div>
      </div>    
      <input type="submit" name="submit" value="submit">
    </form>
  </div>

</body>

</html>