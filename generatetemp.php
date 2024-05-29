<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the Composer autoload file from the local installation

require 'vendor/autoload.php';
use Dompdf\Dompdf;

use Dompdf\Options;

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
// Function to generate Template1

function generateTemplate1($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount)
{
    global $usedQuestionIds;

    if ($difficulty == "easy") {
      $easyCount = (int)(3);  // 50% easy questions
      $moderateCount = (int)(2);  // 30% moderate questions
      $hardCount = (int)(1);  // 20% hard questions
  } elseif ($difficulty == "medium") {
      $easyCount = (int)(0.3 * 6);
      $moderateCount = (int)(0.5 * 6);
      $hardCount = (int)(0.2 * 6);
  } else {
      $easyCount = (int)(0.2 * 6);
      $moderateCount = (int)(0.3 * 6);
      $hardCount = (int)(0.5 * 6);
  }
  
  $difficultyCount = [];
  $difficultyCount = array_merge(
      array_fill(0, $easyCount, "easy"),
      array_fill(0, $moderateCount, "medium"),
      array_fill(0, $hardCount, "hard")
  );
  
shuffle($difficultyCount );
echo '<pre>';
//print_r($difficultyCount);
echo '</pre>';

    ob_start();
    echo "<table style=\"width:100%;border-collapse: collapse;margin-bottom: 0px;padding: 0px;border: 0px solid #fff;\">";
    // Display the generated question paper using Template1
    echo "<table style=\"width:100%;border-collapse: collapse;margin-bottom: 0px;padding: 0px;border: 0px solid #fff;\">";
    // ... (rest of the code remains unchanged)
    echo "<tr>";
    echo "<td colspan=\"3\" style=\"text-align: center;font-weight: bold;\">";
    echo "<tr style=\"font-weight: bold;\">";
    echo "<td colspan=\"3\" style=\"text-align: center;\">$regulation</td>";
    echo "</tr>";
    // regulation name, subject name,college name,month,year,dept name
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
    echo "<th  </th>";
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

// Fetch rows and populate the $unit_vals dictionary
/*while ($row = $result->fetch_assoc()) {
    $unit_vals[$row['unit_value']] = $row['unit_id'];
}*/
// Close the statement
$stmt->close();

    for ($x = 1; $x <= 6; $x++) {
      $questionCounter = 1;
      
    $given_marks = 10;
    $given_regulation = "AR20";
    $given_dept = "INF";
    $given_degree = "BTECH";
    $given_college = "AITAM";
    
$unit_no=$unit_vals[$x];
    $sql = "SELECT q.question_id, q.question, q.marks, q.blooms_level, q.subject_id, q.unit_id, q.imagepath,difficulty	,
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

        
try {
  // Enable MySQLi error reporting
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters (order is adjusted)
  $stmt->bind_param("issssss", $given_marks, $subject, $given_dept, $given_degree, $given_college, $given_regulation,$unit_no);
//var dump
//var_dump($given_marks, $subject, $given_dept, $given_degree, $given_college, $given_regulation);

  // Execute the statement
  $stmt->execute();
  //echo "Executed Query: " . $stmt->sqlstate . PHP_EOL;

  $result = $stmt->get_result();
  $questions = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
  
  // Free the result set
  $result->free_result();

  resetCounter();
  
  if ($stmt->errno) {
      die("Error executing query: " . $stmt->error);
  }
 
  $allQuestions = [];
  foreach ($questions as $row) {
      $allQuestions[] = $row;
  }
  // Shuffle the array
  shuffle($allQuestions);
  
  // Filter the array based on conditions

  $rr=$x-1;
  $filteredQuestions = array_filter($allQuestions, function ($row) use ($rr, $difficultyCount) {
    return $row['marks'] == 10 && $row['difficulty'] == $difficultyCount[$rr++];
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
        $allQuestions = array_slice($alltQuestions, 0, 2);
        foreach ($allQuestions as $row) {
            // Check if the question meets the criteria (unit value = 1 and marks = 10) -- 
            if ($row['unit_id']==$unit_vals[$x]&& $row['marks'] == 10 && !in_array($row['question_id'], $usedQuestionIds)) {
                // Store the question in the selectedQuestions array
                $usedQuestionIds[] = $row['question_id'];

                // Print the question details
                echo "<tr>";
                echo "<td>{$qcount}. </td>";
                echo "<td>{$row['question']}";
                   if($row['imagepath'] != null){ 
                   echo" <img src=\"{$row['imagepath']}\" alt=\"Question image\">";
                }
                echo "</td>";
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$row['marks']}&nbsp;&nbsp;&nbsp;</td>";
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;CO$x</td>"; //** */
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$row['blooms_level']}</td>";
                echo "</tr>";
                $qcount = $qcount + 1;
               
                // Print the 'OR' row after the first question
                if ($questionCounter % 2 == 1) {
                    echo "<tr>";
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
    echo "</table>";
    $paperIndex = time() . '_' . uniqid();
$paperContent = ob_get_clean();
// Generate the PDF using DOMPDF



require 'vendor/autoload.php';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($paperContent);
$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

// Set a random password for the PDF
// Set a random password for the PDF
// Set a random password for the PDF// Set a random password for the PDF
$pdfPassword = generateRandomPassword();
$encryption = new Dompdf\Encryption\Encryption($dompdf->getCanvas());
$encryption->setUserPassword($pdfPassword);

// Save the PDF file
$pdfFileName = "question_paper_$paperIndex.pdf";
$pdfFilePath = __DIR__ . "/../$pdfFileName";
echo "PDF File Path: $pdfFilePath<br>"; // debugging line

// Ensure the directory exists or create it
if (!is_dir(dirname($pdfFilePath))) {
    mkdir(dirname($pdfFilePath), 0755, true);
}

//$pdfFilePath = "C:\xampp\htdocs\question". "/$pdfFileName"; // Dubugging
$dompdf->stream($pdfFilePath);

if (file_exists($pdfFilePath)) { // Debugging line
    echo "PDF saved successfully.<br>"; // Debugging line
} else { // Debugging line
    echo "Error saving PDF.<br>"; // Debugging line
} // Debugging line



// Store the password in a secure way (e.g., database)
// For demonstration purposes, let's assume you have a table named 'pdf_passwords'
$hashedPassword = password_hash($pdfPassword, PASSWORD_BCRYPT);
$sql = "INSERT INTO pdf_passwords (pdf_file_name, hashed_password, orgpassword) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $pdfFileName, $hashedPassword, $pdfPassword);
$stmt->execute();
$stmt->close();

// Provide the link to view the generated paper
$filePath = "http://localhost/question/$pdfFileName";
echo "<p>Question Paper: <a href='$filePath' target='_blank'>View Paper $pdfFileName</a></p>";
}

function generateRandomPassword($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

function generateTemplate($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount)
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
    } elseif ($difficulty == "hard")
    {
        $easyCount = 0.2 * 6;
        $moderateCount = 0.3 * 6;
        $hardCount = 0.5 * 6;
    }else
   {
    
    if ($difficulty == null || $difficulty == "") {
      $difficulty = "2,3,1";
  }
  list($easyCnt, $midCnt, $hardCnt) = array_map('intval', explode(',', $difficulty));
  $easyCount = ($easyCnt / 6) * 6;       // 50% easy questions
  $moderateCount = ($midCnt / 6) * 6;    // 30% moderate questions
  $hardCount = ($hardCnt / 6) * 6;       // 20% hard questions  
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
    echo "<th  </th>";
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

// Fetch rows and populate the $unit_vals dictionary
/*while ($row = $result->fetch_assoc()) {
    $unit_vals[$row['unit_value']] = $row['unit_id'];
}*/
// Close the statement
$stmt->close();

    for ($x = 1; $x <= 6; $x++) {
      $questionCounter = 1;
      
    $given_marks = 10;
    $given_regulation = "AR20";
    $given_dept = "INF";
    $given_degree = "BTECH";
    $given_college = "AITAM";
    
$unit_no=$unit_vals[$x];
    $sql = "SELECT q.question_id, q.question, q.marks, q.blooms_level, q.subject_id, q.unit_id, q.imagepath,
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

        
try {
  // Enable MySQLi error reporting
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters (order is adjusted)
  $stmt->bind_param("issssss", $given_marks, $subject, $given_dept, $given_degree, $given_college, $given_regulation,$unit_no);
//var dump
//var_dump($given_marks, $subject, $given_dept, $given_degree, $given_college, $given_regulation);

  // Execute the statement
  $stmt->execute();
  //echo "Executed Query: " . $stmt->sqlstate . PHP_EOL;

  $result = $stmt->get_result();
  $questions = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
  
  // Free the result set
  $result->free_result();

  resetCounter();
  
  if ($stmt->errno) {
      die("Error executing query: " . $stmt->error);
  }
  
  $allQuestions = [];
  foreach ($questions as $row) {
      $allQuestions[] = $row;
  }
  // Shuffle the array
  shuffle($allQuestions);
  
  // Filter the array based on conditions
  $filteredQuestions = array_filter($allQuestions, function ($row) {
      return $row['marks'] == 10;
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
        $allQuestions = array_slice($alltQuestions, 0, 2);
        foreach ($allQuestions as $row) {
            // Check if the question meets the criteria (unit value = 1 and marks = 10) -- 
            if ($row['unit_id']==$unit_vals[$x]&& $row['marks'] == 10 && !in_array($row['question_id'], $usedQuestionIds)) {
                // Store the question in the selectedQuestions array
                $usedQuestionIds[] = $row['question_id'];

                // Print the question details
                echo "<tr>";
                echo "<td>{$qcount}. </td>";
                echo "<td>{$row['question']}";
                   if($row['imagepath'] != null){ 
                   echo" <img src=\"{$row['imagepath']}\" alt=\"Question image\">";
                }
                echo "</td>";
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$row['marks']}&nbsp;&nbsp;&nbsp;</td>";
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;CO$x</td>"; //** */
                echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;{$row['blooms_level']}</td>";
                echo "</tr>";
                $qcount = $qcount + 1;
               
                // Print the 'OR' row after the first question
                if ($questionCounter % 2 == 1) {
                    echo "<tr>";
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

/*
function generateTemplate2($conn, $regulation, $subject, $difficulty, $noOfPapers, $branch, $YearCount, $semester, $type, $paperIndex, $qcount)
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
echo "<td style=\"text-align: left;\">CODE:20AIT404</td>";
echo "<td></td>";
echo "<td style=\"text-align: right;\">SET-I</td>";
echo "</tr>";
echo "<tr style=\"font-weight: bold;\">";
echo "<td colspan=\"3\" style=\"text-align: center;\">ADITYA INSTITUTE OF TECHNOLOGY AND MANAGEMENT,TEKKALI</td>";
echo "</tr>";
echo "<tr style=\"font-weight: bold;\">";
echo "<td colspan=\"3\" style=\"text-align: center;\">(AUTONOMOUS)</td>";
echo "</tr>";
echo "<tr style=\"font-weight: bold;\">";
echo "<td colspan=\"3\" style=\"text-align: center;\">IV B.Tech I Semester Regular Examination, April-2024</td>";
echo "</tr>";
echo "</td>";
echo "</tr>";
echo "<tr style=\"font-weight: bold;text-align: center;\">";
echo "<td colspan=\"3\">$subject</td>";
echo "</tr>";
echo "<tr style=\"font-weight: bold;text-align: center;\">";
echo "<td colspan=\"3\">($branch)</td>";
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
    echo "<th  </th>";
    echo "<th style=\"text-align: center\">Marks</th>";
    echo "<th style=\"text-align: center\">CO</th>";
    echo "<th style=\"text-align: center\">Blooms Level</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $seed = time();

    // Track used question IDs across papers
    $usedQuestionIds = array();
    if ($qcount >= 12) {
        $qcount = 1;
    }

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

        $sql = "SELECT * FROM importqustions 
                WHERE subject = '$subject' 
                AND regulation = '$regulation'
                AND engineeringBranch = '$branch'
                AND unit = '$x'";

        $questionCounter = 1;
        static $qscount = 1;
        resetCounter();
        $questions = $conn->query($sql);

        if (!$questions) {
            die("Error executing query: " . $conn->error);
        }

        $allQuestions = [];
        $usedQuestionIds=[];
        while ($row = $questions->fetch_assoc()) {
            $allQuestions[] = $row;
        }

        // Shuffle the array
        shuffle($allQuestions);

        // Filter the array based on conditions
        $filteredQuestions = array_filter($allQuestions, function ($row) {
            return $row['marks'] == 5;
        });

        $alltQuestions = array_values($filteredQuestions);
        $allQuestions = array_slice($alltQuestions, 0, 4);

        for ($j = 1; $j <= 2; $j++) {
            if ($qscount >= 3) {
                $qscount = 0;
            }

            if ($allQuestions[$qscount]['unit'] == $x && $allQuestions[$qscount]['marks'] == 5 && !in_array($allQuestions[$qscount]['question'], $usedQuestionIds)) {
                echo "<tr>";
                echo "<td>$qcount</td>";
                echo "<td>";
                echo "<table>";
                echo "<tbody>";
                echo "<tr>";
                echo "<td>a)</td>";
                echo "<td>{$allQuestions[$qscount]['question']}</td>";
                $qscount++;
                echo "</tr>";
                echo "<tr>";
                echo "<td>b)</td>";
                echo "<td>{$allQuestions[$qscount]['question']}</td>";
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
                echo "<td colspan=\"2\" style=\"text-align: center; width: 100%;\">{$allQuestions[$qscount]['marks']}</td>";

                echo "</tr>";
                $qscount++;
                echo "<tr>";
                echo "<td colspan=\"2\" style=\"text-align: center; width: 100%;\">{$allQuestions[$qscount]['marks']}</td>";
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
                echo "<td  colspan=\"2\" style=\"text-align: center;width:100%;\">{$allQuestions[$qscount]['coLevel']}</td>";
                echo "</tr>";
                echo "<tr>";
                $qscount++;
                echo "<td  colspan=\"2\" style=\"text-align: center;width:100%;\">{$allQuestions[$qscount]['coLevel']}</td>";
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
                echo "<td style=\"text-align: center\">{$allQuestions[$qscount]['bloomLevels']}</td>";
                echo "</tr>";
                $qscount++;
                echo "<tr>";
                echo "<td style=\"text-align: center\">{$allQuestions[$qscount]['bloomLevels']}</td>";
                echo "</tr>";
                echo "</tbody>";
                echo "</table>";
                echo"</center>";
                echo "</td>";
                echo "</tr>";
                $qscount++;
                $qcount = $qcount + 1;

                if ($questionCounter % 2 == 1) {
                    echo "<tr>";
                    echo "<td> </td>";
                    echo "<td style=\"text-align: center\">(OR)</td>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "<td> </td>";
                    echo "</tr>";
                    $questionCounter++;
                }
            }
        }
    }
    echo "</tbody>";
    echo "</table>";

    $paperIndex = time() . '_' . uniqid();
    $paperContent = ob_get_clean();
    $fileName = "question_paper_$paperIndex.html";
    file_put_contents($fileName, $paperContent);

    return $fileName;
}
*/
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
      generateTemplate3($$conn, $regulation, $subject, $difficulty, $noOfPapers, $branch, $YearCount, $semester, $type, $paperIndex, $qcount);
  }
}


// Call the function to reset the counter


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
    $fileName = generatePaper($subject, $difficulty, $template, $noOfPapers, $regulation, $department, $YearCount, $semester, $type, $i,$paperIndex,$qcount);
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
        <option value="Template1">Template1</option>
        <option value="Template2">Template2</option>
        <option value="Template3">Template3</option>
      </select>

      <label for="noOfPapers">Number of Papers to Generate:</label>
      <input type="number" id="noOfPapers" name="noOfPapers" min="1" value="1">

      <button type="submit">Generate Papers</button>
    </form>
  </div>
</body>

</html>