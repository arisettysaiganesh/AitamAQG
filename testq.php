<?php
// Place this at the top of your file

// Connect to your database (replace these details with your own)
// Include TCPDF library
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

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
    $paperIndex = time() . '_' . uniqid();
    $fileName = "question_paper_$paperIndex.html";
    $paperContent = ob_get_clean();

    file_put_contents($fileName, $paperContent);

    // Convert the HTML file to PDF using Python
    $htmlFilePath = realpath($fileName);
    $pdfFilePath = "output_$paperIndex.pdf"; // Output PDF file path
    $pythonPath = "C:\\Users\\arise\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
$convertScriptPath = "convert_html_to_pdf.py";
$command = "$pythonPath $convertScriptPath $htmlFilePath $pdfFilePath";

    exec($command, $output, $returnCode);
    if ($returnCode === 0) {
      echo "PDF successfully created!";
  } else {
      echo "Error creating PDF: " . implode("\n", $output);
      return; // Exit the function if PDF creation fails
  }

  // Encrypt the PDF with a randomly generated password
  $password = generateRandomPassword(); // Generate a random password
  $encryptedPdfFilePath = "encrypted_output_$paperIndex.pdf";
  $encryptionCommand = "qpdf --encrypt $password $password 256 -- $pdfFilePath $encryptedPdfFilePath";
  exec($encryptionCommand, $encryptionOutput, $encryptionReturnCode);

  if ($encryptionReturnCode === 0) {
      echo "PDF encrypted successfully with password: $password";
  } else {
      echo "Error encrypting PDF: " . implode("\n", $encryptionOutput);
      return; // Exit the function if encryption fails
  }

  // Insert password details into the database table
  $pdfFileName = basename($encryptedPdfFilePath);
  $pdfPassword = $password;
  $hashedPassword = password_hash($pdfPassword, PASSWORD_BCRYPT);
  $sql = "INSERT INTO pdf_passwords (pdf_file_name, hashed_password, orgpassword) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $pdfFileName, $hashedPassword, $pdfPassword);
  $stmt->execute();
  $stmt->close();

  // Return the encrypted PDF file path
  return $encryptedPdfFilePath;

   // Generate the PDF using TCPDF


   
  echo $paperContent;  // Debugging line
   $pdf = new TCPDF();
   $pdf->AddPage();
   $pdf->SetFont('helvetica', '', 12);
   $pdf->writeHTML($paperContent, true, false, true, false, '');
   // Set a random password for the PDF
   $pdfPassword = generateRandomPassword();
   $pdf->SetProtection(['print', 'copy'], $pdfPassword, '', 0, null);
   // Save the PDF file
   $pdfFileName = "question_paper_$paperIndex.pdf";
   //file_put_contents($pdfFileName, $paperContent);
   $pdfFilePath = __DIR__ . DIRECTORY_SEPARATOR . $pdfFileName;

   
   echo "PDF File Path: $pdfFilePath<br>"; // debugging line
   

   // Ensure the directory exists or create it
if (!is_dir(dirname($pdfFilePath))) {
  mkdir(dirname($pdfFilePath), 0755, true);
}

   //$pdfFilePath = "C:\xampp\htdocs\question". "/$pdfFileName"; // Dubugging
   $pdf->Output($pdfFilePath, 'F');
   
   if ($pdf->Output($pdfFilePath, 'F')) { // Debugging line
    echo "PDF saved successfully.<br>";// Debugging line
    //echo"<script>alert(\"Hello! I am an alert box!\");</script>";
} else {// Debugging line
    echo "Error saving PDF.<br>";// Debugging line
}// Debugging line

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
   // echo "<table style=\"width:auto; border-collapse: collapse;\">";

  
    //regulation,branch,YearCount,semester,type
    for ($i = 1; $i <= $noOfPapers; $i++) {
      $regulation = $_POST['regulation'];
      $paperIndex = time(); // Initialize $paperIndex
      generateTemplate1($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $i, $paperIndex, $qcount);
    }
    
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
 
</head>
<iframe src="ddd.html" frameborder="0" width="100%" height="120px"></iframe>
<body>


  <div class="paper-generator">
    <h2>Paper Generator</h2>
    <form action="#" method="post" id="paperForm">
      <label for="regulation">Regulation:</label>
      <select id="regulation" name="regulation">
        <option value="AR20">2020</option>
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