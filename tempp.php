<?php
// Connect to your database (replace these details with your own)
$servername = "localhost";
$username = "root";
$password = "";
$database = "paper";

$conn = new mysqli($servername, $username, $password, $database);
static $qcount=1;
static $usedQuestionIds=array();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function resetCounter() {
  static $qcount = 1;
  $qcount=1;
}
function generateTemplate3($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount)
{
    global $usedQuestionIds;

    ob_start();
    // Display the generated question paper using Template1
    echo "<table style=\"width:100%;border-collapse: collapse;margin-bottom: 0px;padding: 0px;border: 0px solid #fff;\">";
    // ... (rest of the code remains unchanged)
    echo "<table style=\"width:100%;border-collapse: collapse;margin-bottom: 0px;border: 0px solid #fff;\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th> </th>";
    echo "<th  </th>";
    echo "<th style=\"text-align: center\">Marks</th>";
    echo "<th style=\"text-align: center\">CO</th>";
    echo "<th style=\"text-align: center\">Blooms Level</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Fetch unit values
    $unit_vals = [];
    $sqlu_id = "SELECT unit_value, unit_id FROM unit u WHERE u.regulation_id = ? AND u.subject_id = ?";
    $stmt = $conn->prepare($sqlu_id);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ss", $regulation, $subject);
    $stmt->execute();

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $unit_vals[$row['unit_value']] = $row['unit_id'];
    }

    $stmt->close();

    for ($x = 1; $x <= 6; $x++) {
        echo "<thead>";
        echo "<tr>";
        echo "<th> </th>";
        echo "<th style=\"text-align: center\">UNIT-$x</th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "</tr>";
        echo "</thead>";

        $sql = "SELECT q.question_id, q.question, q.marks, q.blooms_level, q.subject_id, q.unit_id, q.imagepath, u.co
                FROM question q
                JOIN unit u ON q.unit_id = u.unit_id
                WHERE q.marks = ? AND q.subject_id = ? AND q.unit_id = ?";

        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $marks, $subject, $unit_vals[$x]);

            $marks = 5;
            $stmt->execute();
            $result = $stmt->get_result();
            $questions = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
            $result->free_result();

            $marks = 10;
            $stmt->execute();
            $result = $stmt->get_result();
            $questions_10_marks = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
            $result->free_result();

            $stmt->close();

            $usedQuestionIds = [];

            // Shuffle the arrays
            shuffle($questions);
            shuffle($questions_10_marks);

            // Choose 2 questions for 5 marks
            $selected_questions_5_marks = array_slice($questions, 0, 2);

            // Choose 1 question for 10 marks
            $selected_question_10_marks = array_shift($questions_10_marks);

            // Merge selected questions
            $selected_questions = array_merge($selected_questions_5_marks, [$selected_question_10_marks]);
            $counter=0; 
            static $qc=0;
            foreach ($selected_questions as $key => $row) {
                static $counter=0;                
                echo "<tr>";
                echo "<td>" . ($qc + 1) . ". </td>";
                echo "<td>{$row['question']}";
                if ($row['imagepath'] != null) {
                    echo " <img src=\"{$row['imagepath']}\" alt=\"Question image\">";
                }
                echo "</td>";
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$row['marks']}&nbsp;&nbsp;&nbsp;</td>";
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$row['co']}</td>";
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$row['blooms_level']}</td>";
                echo "</tr>";
                $counter++;
                $qc++;
                if($counter %1==0 && $counter %2==0 && $counter %3!=0 && $counter %4!=0)
                {
                    echo "<tr>";
                    echo "<td> </td>";
                    echo "<td style=\"text-align: center\">(OR)</td>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "</tr>";
                }
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    echo "</tbody>";
    echo "</table>";
    $paperIndex = time() . '_' . uniqid();
    $paperContent = ob_get_clean();
    $fileName = "question_paper_$paperIndex.html";
    file_put_contents($fileName, $paperContent);

    // Return the file name
    return $fileName;
}
function generateTemplate2($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $papeyrIndex, $qcount)
{
    global $usedQuestionIds;

    if ($difficulty == "easy") {
        $easyCount = 0.5 * 6;  // 50% easy questions
        $moderateCount = 0.3 * 6;  // 30% moderate questions
        $hardCount = 0.2 * 6;  // 20% hard questions
    } elseif ($difficulty == "medium") {
        $easyCount = 0.3 * 6;
        $moderateCount = 0.5 * 6;
        $hardCount = 0.2 * 6;
    } else {
        $easyCount = 0.2 * 6;
        $moderateCount = 0.3 * 6;
        $hardCount = 0.5 * 6;
    }

    ob_start();
    // Display the generated question paper using Template1
    echo "<table style=\"width:100%;border-collapse: collapse;margin-bottom: 0px;padding: 0px;border: 0px solid #fff;\">";
    // ... (rest of the code remains unchanged)
    echo "<tr>";
    echo "<td colspan=\"3\" style=\"text-align: center;font-weight: bold;\">";
    echo "<tr style=\"font-weight: bold;\">";
    echo "<td colspan=\"3\" style=\"text-align: center;\">$regulation</td>";
    echo "</tr>";
    echo "<tr style=\"font-weight: bold;\">";
    echo "<td style=\"text-align: left;\">CODE:$subject</td>";
    echo "<td></td>";
    echo "<td style=\"text-align: right;\">SET-I</td>"; //****
    echo "</tr>";
    echo "<tr style=\"font-weight: bold;\">";
    echo "<td colspan=\"3\" style=\"text-align: center;\">ADITYA INSTITUTE OF TECHNOLOGY AND MANAGEMENT,TEKKALI</td>";
    echo "</tr>";
    echo "<tr style=\"font-weight: bold;\">";
    echo "<td colspan=\"3\" style=\"text-align: center;\">(AUTONOMOUS)</td>";
    echo "</tr>";
    echo "<tr style=\"font-weight: bold;\">";
    echo "<td colspan=\"3\" style=\"text-align: center;\">$YearCount B.Tech $semester Semester Regular Examination, April-2024</td>";
    echo "</tr>";
    echo "</td>";
    echo "</tr>";
    echo "<tr style=\"font-weight: bold;text-align: center;\">";
    echo "<td colspan=\"3\"> $subject</td>";
    echo "</tr>";
    echo "<tr style=\"font-weight: bold;text-align: center;\">";
    echo "<td colspan=\"3\">($department) TEMP BRANCH</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td style=\"text-align: left;\">Time: 3 hours</td>";
    echo "<td></td>";
    echo "<td style=\"text-align: right;\">Max Marks: 60</td>";
    echo "</tr>";
    echo "<tr style=\"text-align: center;\">";
    echo "<td colspan=\"3\">Answer ONE Question from each Unit</td>";
    echo "</tr>";
    echo "<tr style=\"text-align: center;\">";
    echo "<td colspan=\"3\">All Questions Carry Equal Marks</td>";
    echo "</tr>";
    echo "<tr style=\"text-align: center;\">";
    echo "<td colspan=\"3\">All parts of the Question must be answered at one place</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table style=\"width:100%;border-collapse: collapse;margin-bottom: 0px;border: 0px solid #fff;\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th> </th>";
    echo "<th>  </th>";
    echo "<th>  </th>";
    echo "<th style=\"text-align: center\">Marks</th>";
    echo "<th style=\"text-align: center\">CO</th>";
    echo "<th style=\"text-align: center\">Blooms Level</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $seed = time();

    // Track used question IDs across papers

    if ($qcount >= 12) {
        $qcount = 1;
    }

$unit_vals = []; // Initialize an empty array
    
$sqlu_id = "SELECT unit_value, unit_id FROM unit u WHERE u.regulation_id = ? AND u.subject_id = ?";
$stmt = $conn->prepare($sqlu_id);


// Check if the statement preparation was successful
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters and execute the query
$stmt->bind_param("ss", $regulation, $subject);
$stmt->execute();

// Check if the execution was successful
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

// Get the result
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $unit_vals[$row['unit_value']] = $row['unit_id'];
}

// Close the statement
$stmt->close();

    for ($x = 1; $x <= 6; $x++)
    {
        
        $questionCounter = 1;
        static $qscount = 1;
        resetCounter();

        $given_regulation = "AR20";
        $given_dept = "INF";
        $given_degree = "BTECH";
        $given_college = "AITAM";
        $unit_no=$unit_vals[$x];
            // Retrieve 5-marks questions for (a) and (b)
          $sql_5_marks= "SELECT q.question_id, q.question, q.marks, q.blooms_level, q.subject_id, q.unit_id, q.imagepath,u.co,
            s.subject_name
            FROM question q
            JOIN unit u ON q.unit_id = u.unit_id
            JOIN subjects s ON q.subject_id = s.subject_id
            JOIN department d ON s.dept_id = d.dept_id
            JOIN degree deg ON d.degree_id = deg.degree_id
            JOIN college c ON deg.college_id = c.college_id
            JOIN regulations r ON deg.degree_id = r.degree_id
            WHERE q.marks = ?
            AND s.subject_id = ?
            AND d.dept_id = ?
            AND deg.degree_id = ?
            AND c.college_id = ?
            AND r.regulation_id = ?
            AND u.unit_id=?";
            
        try 
        {
         // Enable MySQLi error reporting
         mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

         // Prepare the statement
         $stmt_5_marks= $conn->prepare($sql_5_marks);
         $marks=5;
         // Bind parameters (order is adjusted)
         $stmt_5_marks->bind_param("issssss",$marks, $subject, $given_dept, $given_degree, $given_college, $given_regulation,$unit_no);
         // Execute the statement
         $stmt_5_marks->execute();
         //echo "Executed Query: " . $stmt->sqlstate . PHP_EOL;
          
         $result_5_marks =  $stmt_5_marks->get_result();
         
         $questions_5_marks = $result_5_marks->num_rows > 0 ? $result_5_marks->fetch_all(MYSQLI_ASSOC) : [];
        
         // Free the result set
         $result_5_marks->free_result();
        
         if ($stmt_5_marks->errno) {
            die("Error executing query: " . $stmt_5_marks->error);
        }
        
        $all_5_Questions = [];
        foreach ($questions_5_marks as $row) {
            $all_5_Questions[] = $row;
        }
        // Shuffle the array
        shuffle($all_5_Questions);
        
        // Filter the array based on conditions
        $filteredQuestions = array_filter($all_5_Questions, function ($row) {
            return $row['marks'] == 5;
        });

        } catch (Exception $e) {
        die("Error: " . $e->getMessage());
        }
            
            echo "<thead>";
            echo "<tr>";
            echo "<th> </th>";
            echo "<th style=\"text-align: center\">UNIT-$x</th>";
            echo "<th></th>";
            echo "<th></th>";
            echo "<th></th>";
            echo "</tr>";
            echo "</thead>";
         //$result = $stmt->get_result();
         $usedQuestionIds = [];
         $alltQuestions = array_values($filteredQuestions);

        // Select only two 5-marks questions for (a) and (b)
         $all5Questions = array_slice($alltQuestions , 0, 4);
         //print_r($all5Questions);
         
        for ($j = 1; $j <= 2; $j++) {
            if ($qscount >= 3) {
                $qscount = 0;
            }

            if (isset($all5Questions[$qscount])&&$all5Questions[$qscount]['unit_id'] == $unit_vals[$x] && $all5Questions[$qscount]['marks'] == 5 && !in_array($all5Questions[$qscount]['question_id'], $usedQuestionIds)) {
                    // Store the question in the selectedQuestions array
                    $usedQuestionIds[] = $row['question_id'];
                
                    //to print sub questions
                    echo "<tr>";
                    echo "<td>{$qcount}.</td>";
                    echo "<td>";
                    echo "<table>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td>(a)</td>";
                    echo "<td>{$all5Questions[$qscount]['question']}";//first sub question
                    if($row['imagepath'] != null){ 
                    echo" <img src=\"{$row['imagepath']}\" alt=\"Question image\">";
                    }
                    echo "</td>";
                    $qscount++;
                    echo "<tr>";
                    
                    echo "<td>(b)</td>";
                    echo "<td>{$all5Questions[$qscount]['question']}";
                    if($row['imagepath'] != null){ 
                    echo" <img src=\"{$row['imagepath']}\" alt=\"Question image\">";
                    }
                    echo "</td>";
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "</td>";

                    echo "<td style=\"text-align: center\">";
                    echo"<center>";
                    echo "<table>";
                    echo "<tbody>";
                    $qscount--;
                    echo "<tr>";
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['marks']}&nbsp;&nbsp;&nbsp;</td>";
                    echo "</tr>";
                    $qscount++;
                    echo "<tr>";
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['marks']}&nbsp;&nbsp;&nbsp;</td>";
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo"</center>";
                    echo "</td>";
                    echo "<td style=\"text-align: center;\">";
                    echo"<center>";
                    echo "<table>";
                    echo "<tbody>";
                    $qscount--;
                    echo "<tr>";
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['co']}</td>"; //** */
                    echo "</tr>";
                    echo "<tr>";
                    $qscount++;
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['co']}</td>"; //** */
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo"</center>";
                    echo "</td>";
                    echo "<td style=\"text-align: center;\">";
                    echo"<center>";
                    echo "<table>";
                    echo "<tbody>";
                    echo "<tr>";
                    $qscount--;
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['blooms_level']}</td>";
                    echo "</tr>";
                    $qscount++;
                    echo "<tr>";
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['blooms_level']}</td>";
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo"</center>";
                    echo "</td>";
                    echo "</tr>";
                    $qscount++;

                     // Increment the question counter
                   
                    $qcount = $qcount + 1;
                    
                    // Print the 'OR' row after the first question
                if ($questionCounter % 2 == 1) {
                    echo "<tr>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "<td style=\"text-align: center\">(OR)</td>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "</tr>";
                    $questionCounter++;
                    
                }
                 // Increment the question counter
                    
                }
              }
            }
        echo "</tbody>";
        echo "</table>";
        $paperIndex = time() . '_' . uniqid();
        $paperContent = ob_get_clean();
        $fileName = "question_paper_$paperIndex.html";
        file_put_contents($fileName, $paperContent);

        // Return the file name
        return $fileName;
    
}
function generatePaper($subject, $difficulty, $template,$noOfPapers,$regulation,$branch,$YearCount,$semester,$type,$paperIndex,$qcount)
{
    global $conn;

  // Escape and format values for the SQL query
  $subject = mysqli_real_escape_string($conn, $subject);
  $difficulty = mysqli_real_escape_string($conn, $difficulty);
  $regulation = mysqli_real_escape_string($conn, $regulation);
  $branch = mysqli_real_escape_string($conn, $branch);
  
  // Generate papers based on the selected template
  if ($template == 'Template1') {
    $marks=10;
      generateTemplate1($conn, $regulation, $subject, $difficulty,$noOfPapers,$branch,$YearCount,$semester,$type,$paperIndex,$qcount); 
      // Pass $questions to generateTemplate1
  } elseif ($template == 'Template2') {
      generateTemplate2($conn, $regulation, $subject, $difficulty, $noOfPapers, $branch, $YearCount, $semester, $type, $paperIndex, $qcount);
  } elseif ($template == 'Template3') {
      generateTemplate3($conn, $regulation, $subject, $difficulty, $noOfPapers, $branch, $YearCount, $semester, $type, $paperIndex, $qcount);
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $difficulty = $_POST['difficulty'];
    $template = $_POST['template'];
    $noOfPapers = $_POST['noOfPapers'];
    $regulation= $_POST['regulation'];
    $department= $_POST['branch'];
    $YearCount= $_POST['YearCount'];
    $semester= $_POST['semester'];
    $type= $_POST['type'];
    echo "<table style=\"width:auto; border-collapse: collapse;\">";

  
    //regulation,branch,YearCount,semester,type
    for ($i = 1; $i <= $noOfPapers; $i++) {
    $regulation= $_POST['regulation'];
    $paperIndex = time(); // Initialize $paperIndex
    $fileName = generatePaper($subject, $difficulty, $template, $noOfPapers, $regulation, $department, $YearCount, $semester, $type, $i, $paperIndex, $qcount);
    echo "<tr>";
    echo "<td>";
    // Provide a hyperlink to view the generated paper
    $filePath = "http://localhost/Project/$fileName";
    echo "<p>Question Paper $i: <a href='$filePath' target='_blank'>View Paper $fileName</a></p>";
    echo "</td>";
    echo "</tr>";
    
        //.......................
        
        
    }
    echo "</table>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Paper Generator</title>
  <style>body {
  margin: 0;
  padding: 0;
  font-family: 'Arial', sans-serif;
  background: linear-gradient(to right, #6bb9f0, #3498db);
}

.paper-generator {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  width: 30%; /* Adjust the width as needed */
  text-align: center;
  margin: auto;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #333; /* Label color */
}


select, input {
  width: 100%;
  padding: 10px;
  margin-bottom: 16px;
  box-sizing: border-box;
  border: 1px solid #ccc; /* Input border color */
  border-radius: 5px;
}


button {
  background-color: #4caf50;
  color: #fff;
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease; /* Smooth transition effect */
}

button:hover {
  background-color: #45a049;
}

/* Animation for the submit button */
@keyframes buttonAnimation {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.animated-button {
  animation: buttonAnimation 1s infinite; /* Run the animation infinitely */
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

table, th, td {
  border: 1px solid black;
}

  </style>
</head>

<body>

  <div class="paper-generator">
    <h2>Paper Generator</h2>
    <form action="#" method="post" id="paperForm">
      <label for="regulation">Regulation:</label>
      <select id="regulation" name="regulation">
        <option value="AR20">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
      </select>

      <label for="branch">Branch:</label>
      <select id="branch" name="branch">
      
        <option value="IT">Computer Science and Engineering</option>
        <option value="ece">Electronics and Communication Engineering</option>
        <!-- Add more options as needed -->
      </select>

      <label for="Year">Year:</label>
      <select id="YearCount" name="YearCount">
        <option value="I">I</option>
        <option value="II">II</option>
        <option value="III">III</option>
        <option value="IV">IV</option>
      </select>

      <label for="semester">Semester:</label>
      <select id="semester" name="semester">
        <option value="I">I</option>
        <option value="II">II</option>
        <!-- Add more options as needed -->
      </select>

      <label for="subject">Subject:</label>
      <select id="subject" name="subject">
        <option value="20DSHT107">CHEMISTRY</option>
        <option value="cse103">cse103 - Database Management Systems</option>
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
        <!-- Add more options as needed -->
      </select>

      <label for="type">Type of Paper:</label>
      <select id="type" name="type">
        <option value="60">Semester - 60 Marks</option>
        <option value="30">Mid - 30 Marks</option>
      </select>

      <label for="difficulty">Difficulty:</label>
      <select id="difficulty" name="difficulty">
        <option value="easy">Easy</option>
        <option value="medium">Medium</option>
        <option value="hard">Hard</option>
      </select>

      <label for="template">Question Template:</label>
      <select id="template" name="template">
      <option value="Template3">Template3</option>
      <option value="Template2">Template2</option>
        <option value="Template1">Template1</option>
        
        
      </select>

      <label for="noOfPapers">Number of Papers to Generate:</label>
      <input type="number" id="noOfPapers" name="noOfPapers" min="1" value="1">

      <button type="submit">Generate Papers</button>
    </form>
  </div>
</body>
</html>