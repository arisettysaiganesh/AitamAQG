<?php
// Place this at the top of your file

// Connect to your database (replace these details with your own)
// Include TCPDF library
$servername = "localhost";
$username = "root";
$password = "";
$database = "paper";

require_once 'bloomsfun.php';



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
function generateTemplate1($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount,$minRequirements)
{

  // Define minimum requirements for each Bloom's level


// Sort minRequirements in descending order of values
arsort($minRequirements);
 // Initialize counts for each Bloom's level
 $levelCounts = array_fill_keys(array_keys($minRequirements), 0);
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
$subject = mysqli_real_escape_string($conn, $subject);
$regulation = mysqli_real_escape_string($conn, $regulation);

// Modify the SQL query to join with relevant tables
$sql1 = "SELECT r.regulation_name, s.subject_name, c.college_name, d.dept_name
    FROM regulations r
    JOIN subjects s ON r.regulation_id = s.regulation_id
    JOIN department d ON s.dept_id = d.dept_id
    JOIN degree deg ON d.degree_id = deg.degree_id
    JOIN college c ON deg.college_id = c.college_id
    WHERE s.subject_id = ?
    AND r.regulation_id = ?";

    $stmt = $conn->prepare($sql1);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ss", $subject, $regulation);
    $stmt->execute();

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        $regulationName = $data['regulation_name'];
        $subjectName = $data['subject_name'];
        $collegeName = $data['college_name'];
        $deptName = $data['dept_name'];

        
    } else {
        echo "No results found.";
    }

    $stmt->close();

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
echo "<td colspan=\"3\" style=\"text-align: center;\">$collegeName</td>";
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
echo "<td colspan=\"3\"> $subjectName</td>";
echo "</tr>";
echo "<tr style=\"font-weight: bold;text-align: center;\">";
echo "<td colspan=\"3\">$deptName</td>";
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
    $xx=$x;
$unit_no=$unit_vals[$xx];

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
// Function to select questions based on Bloom's levels and minimum requirements

 $filteredQuestions = selectQuestions($filteredQuestions, $minRequirements, $levelCounts);
 $encryption_key = "AITAM2024";
        // Decrypt each question in the $allQuestions array
        foreach ($filteredQuestions as &$question) {
          if (isset($question['question'])) {
           
              $question['question'] = decrypt_data($question['question'], $encryption_key);
          } else {
              echo "Error: 'question' key not found in array element.";
          }
        }

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
            if ($row['unit_id']==$unit_vals[$xx]&& $row['marks'] == 10 && !in_array($row['question_id'], $usedQuestionIds)) {
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

    // Generate a random password for the PDF
$pdfPassword = generateRandomPassword(); // Define this function to generate a random password
// Hash the password
$hashedPassword = password_hash($pdfPassword, PASSWORD_BCRYPT);

// Convert the HTML file to PDF using Python
$htmlFilePath = realpath($fileName);
$pdfDirectory = "C:\\xampp\\htdocs\\question";  // Define the directory path
$pdfFileName = "output_$paperIndex.pdf"; // Output PDF file name
$pdfFilePath = $pdfDirectory . "\\" . $pdfFileName;  // Combine directory path and file name

$pythonPath = "C:\\Users\\arise\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
$convertScriptPath = "convert_html_to_pdf.py";

$command = "$pythonPath $convertScriptPath $htmlFilePath $pdfFilePath $pdfPassword"; // Pass the password as an argument to the Python script

// Insert the data into the database
$sql = "INSERT INTO pdf_passwords (pdf_file_name, hashed_password, orgpassword) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $pdfFileName, $hashedPassword, $pdfPassword);
$stmt->execute();
$stmt->close();
exec($command, $output, $returnCode);
echo $pdfPassword;
if ($returnCode === 0) {
    echo "PDF successfully created!";
} else {
    echo "Error creating PDF: " . implode("\n", $output);
    return; // Exit the function if PDF creation fails
}
   
  echo "entered python";

  
}
// Function to generate Template2 

function generateTemplate2($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount,$minRequirements)
{   
  
  arsort($minRequirements);
  // Initialize counts for each Bloom's level
  $levelCounts = array_fill_keys(array_keys($minRequirements), 0);
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
$subject = mysqli_real_escape_string($conn, $subject);
$regulation = mysqli_real_escape_string($conn, $regulation);

// Modify the SQL query to join with relevant tables
$sql1 = "SELECT r.regulation_name, s.subject_name, c.college_name, d.dept_name
    FROM regulations r
    JOIN subjects s ON r.regulation_id = s.regulation_id
    JOIN department d ON s.dept_id = d.dept_id
    JOIN degree deg ON d.degree_id = deg.degree_id
    JOIN college c ON deg.college_id = c.college_id
    WHERE s.subject_id = ?
    AND r.regulation_id = ?";

    $stmt = $conn->prepare($sql1);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ss", $subject, $regulation);
    $stmt->execute();

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        $regulationName = $data['regulation_name'];
        $subjectName = $data['subject_name'];
        $collegeName = $data['college_name'];
        $deptName = $data['dept_name'];

        
    } else {
        echo "No results found.";
    }

    $stmt->close();

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
echo "<td colspan=\"3\" style=\"text-align: center;\">$collegeName</td>";
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
echo "<td colspan=\"3\"> $subjectName</td>";
echo "</tr>";
echo "<tr style=\"font-weight: bold;text-align: center;\">";
echo "<td colspan=\"3\">$deptName</td>";
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

    if ($qcount >= 18) {
      $qcount = 1;
  }
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
      $questionCounter = 1;
      $given_regulation = "AR20";
      $given_dept = "INF";
      $given_degree = "BTECH";
      $given_college = "AITAM";

        echo "<thead>";
        echo "<tr>";
        echo "<th> </th>";
        echo "<th style=\"text-align: center\">UNIT-$x</th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "</tr>";
        echo "</thead>";

        $sql = "SELECT q.question_id, q.question, q.marks, q.blooms_level, q.subject_id, q.unit_id, q.imagepath,difficulty, u.co
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
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssss", $marks, $subject, $given_dept, $given_degree, $given_college, $given_regulation,$unit_vals[$x]);


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

            $rr=$x-1;
            $questions = array_filter($questions, function ($row) use ($rr, $difficultyCount) {
              return $row['marks'] == 5 && $row['difficulty'] == $difficultyCount[$rr++];
          });

          
         $rr=$x-1;
            $questions_10_marks = array_filter($questions_10_marks, function ($row) use ($rr, $difficultyCount) {
              return $row['marks'] == 10 && $row['difficulty'] == $difficultyCount[$rr++];
          });

          $questions = selectQuestions($questions, $minRequirements, $levelCounts);
          $questions_10_marks = selectQuestions($questions_10_marks, $minRequirements, $levelCounts);

            // Choose 2 questions for 5 marks
            $selected_questions_5_marks = array_slice($questions, 0, 2);

            // Choose 1 question for 10 marks
            $selected_question_10_marks = array_shift($questions_10_marks);

            // Merge selected questions
            $selected_questions = array_merge($selected_questions_5_marks, [$selected_question_10_marks]);
            $counter=0; 
            static $qc=0;
            $encryption_key = "AITAM2024";
       
        // Decrypt each question in the $allQuestions array
foreach ($selected_questions as &$question) {
  if (isset($question['question'])) {
   
      $question['question'] = decrypt_data($question['question'], $encryption_key);
  } else {
      echo "Error: 'question' key not found in array element.";
  }
}
            foreach ($selected_questions as $key => $row) {
                static $counter=0;                
                echo "<tr>";
                echo "<td>" . ($qc + 1) .". </td>";
                echo "<td>";
                // Check if the 'question' key exists and is not null
                if (isset($row['question']) && $row['question'] !== null) {
                    echo $row['question'];
                }
                if($row['imagepath'] != null){ 
                  echo" <img src=\"{$row['imagepath']}\" alt=\"Question image\">";
               }
               echo "</td>";
                // Check if the 'marks' key exists and is not null
                echo "<td style=\"text-align: center\">";
                if (isset($row['marks']) && $row['marks'] !== null) {
                    echo "&nbsp;&nbsp;&nbsp;{$row['marks']}&nbsp;&nbsp;&nbsp;";
                }
                echo "</td>";
                // Check if the 'co' key exists and is not null
                echo "<td style=\"text-align: center\">";
                if (isset($row['co']) && $row['co'] !== null) {
                    echo "&nbsp;&nbsp;&nbsp;{$row['co']}";
                }
                echo "</td>";
                // Check if the 'blooms_level' key exists and is not null
                echo "<td style=\"text-align: center\">";
                if (isset($row['blooms_level']) && $row['blooms_level'] !== null) {
                    echo "&nbsp;&nbsp;&nbsp;{$row['blooms_level']}";
                }
                echo "</td>";
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
    $paperIndex = time() . '_' . uniqid();
    $fileName = "question_paper_$paperIndex.html";
    $paperContent = ob_get_clean();

    file_put_contents($fileName, $paperContent);

    // Generate a random password for the PDF
$pdfPassword = generateRandomPassword(); // Define this function to generate a random password
// Hash the password
$hashedPassword = password_hash($pdfPassword, PASSWORD_BCRYPT);

// Convert the HTML file to PDF using Python
$htmlFilePath = realpath($fileName);
$pdfDirectory = "C:\\xampp\\htdocs\\question";  // Define the directory path
$pdfFileName = "output_$paperIndex.pdf"; // Output PDF file name
$pdfFilePath = $pdfDirectory . "\\" . $pdfFileName;  // Combine directory path and file name

$pythonPath = "C:\\Users\\arise\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
$convertScriptPath = "convert_html_to_pdf.py";

$command = "$pythonPath $convertScriptPath $htmlFilePath $pdfFilePath $pdfPassword"; // Pass the password as an argument to the Python script

// Insert the data into the database
$sql = "INSERT INTO pdf_passwords (pdf_file_name, hashed_password, orgpassword) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $pdfFileName, $hashedPassword, $pdfPassword);
$stmt->execute();
$stmt->close();
exec($command, $output, $returnCode);
echo $pdfPassword;
if ($returnCode === 0) {
    echo "PDF successfully created!";
} else {
    echo "Error creating PDF: " . implode("\n", $output);
    return; // Exit the function if PDF creation fails
}
   
  echo "entered python";

 
}
// Function to generate Template3
function generateTemplate3($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount,$minRequirements)
{
    global $usedQuestionIds;

    arsort($minRequirements);
    // Initialize counts for each Bloom's level
    $levelCounts = array_fill_keys(array_keys($minRequirements), 0);
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
  $subject = mysqli_real_escape_string($conn, $subject);
  $regulation = mysqli_real_escape_string($conn, $regulation);
  
  // Modify the SQL query to join with relevant tables
  $sql1 = "SELECT r.regulation_name, s.subject_name, c.college_name, d.dept_name
      FROM regulations r
      JOIN subjects s ON r.regulation_id = s.regulation_id
      JOIN department d ON s.dept_id = d.dept_id
      JOIN degree deg ON d.degree_id = deg.degree_id
      JOIN college c ON deg.college_id = c.college_id
      WHERE s.subject_id = ?
      AND r.regulation_id = ?";
  
      $stmt = $conn->prepare($sql1);
  
      if (!$stmt) {
          die("Error preparing statement: " . $conn->error);
      }
  
      $stmt->bind_param("ss", $subject, $regulation);
      $stmt->execute();
  
      if (!$stmt->execute()) {
          die("Error executing query: " . $stmt->error);
      }
  
      $result = $stmt->get_result();
      $data = $result->fetch_assoc();
  
      if ($result->num_rows > 0) {
          $regulationName = $data['regulation_name'];
          $subjectName = $data['subject_name'];
          $collegeName = $data['college_name'];
          $deptName = $data['dept_name'];
  
          
      } else {
          echo "No results found.";
      }
  
      $stmt->close();
  
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
  echo "<td colspan=\"3\" style=\"text-align: center;\">$collegeName</td>";
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
  echo "<td colspan=\"3\"> $subjectName</td>";
  echo "</tr>";
  echo "<tr style=\"font-weight: bold;text-align: center;\">";
  echo "<td colspan=\"3\">$deptName</td>";
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
    //echo "<th>  </th>";
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
          $sql_5_marks= "SELECT q.question_id, q.question, q.marks, q.blooms_level, q.subject_id, q.unit_id, q.imagepath,u.co,difficulty,
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

        $rr=$x-1;
        $all_5_Questions = array_filter($filteredQuestions, function ($row) use ($rr, $difficultyCount) {
          return $row['marks'] == 5 && $row['difficulty'] == $difficultyCount[$rr++];
      });
      $all_5_Questions = selectQuestions($all_5_Questions, $minRequirements, $levelCounts);

        } catch (Exception $e) {
        die("Error: " . $e->getMessage());
        }
            
            echo "<thead>";
            echo "<tr>";
            echo "<th> </th>";
            echo "<th style=\"text-align: center\">UNIT-$x</th>";
            //echo "<th></th>";
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
         $encryption_key = "AITAM2024";
       
        // Decrypt each question in the $allQuestions array
        foreach ($all5Questions as &$question) {
          if (isset($question['question'])) {
          
              $question['question'] = decrypt_data($question['question'], $encryption_key);
          } else {
              echo "Error: 'question' key not found in array element.";
          }
        }
        for ($j = 1; $j <= 2; $j++) {
            if ($qscount >= 3) {
                $qscount = 0;
            }

            if (!empty($all5Questions[$qscount])&&isset($all5Questions[$qscount])&&$all5Questions[$qscount]['unit_id'] == $unit_vals[$x] && $all5Questions[$qscount]['marks'] == 5 && !in_array($all5Questions[$qscount]['question_id'], $usedQuestionIds)) {
                    // Store the question in the selectedQuestions array
                    $usedQuestionIds[] = $row['question_id'];
                
                    //to print sub questions
                    echo "<tr>";
                    echo "<td>{$qcount}.</td>";
                    echo "<td>";
                    echo "<table>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>(a)</td>";
                    echo "<td>{$all5Questions[$qscount]['question']}";//first sub question
                    if($row['imagepath'] != null){ 
                    echo" <img src=\"{$row['imagepath']}\" alt=\"Question image\">";
                    }
                    echo "</td>";
                    $qscount++;
                    echo "<tr>";
                    echo "<td></td>";
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
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['marks']}&nbsp;&nbsp;&nbsp;</td>";
                    echo "</tr>";
                    $qscount++;
                    echo "<tr>";
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['marks']}&nbsp;&nbsp;&nbsp;</td>";
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
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['co']}</td>"; //** */
                    echo "</tr>";
                    echo "<tr>";
                    $qscount++;
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['co']}</td>"; //** */
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
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['blooms_level']}</td>";
                    echo "</tr>";
                    $qscount++;
                    echo "<tr>";
                    echo "<td style=\"text-align: center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$all5Questions[$qscount]['blooms_level']}</td>";
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
    $paperIndex = time() . '_' . uniqid();
    $fileName = "question_paper_$paperIndex.html";
    $paperContent = ob_get_clean();

    file_put_contents($fileName, $paperContent);

    // Generate a random password for the PDF
$pdfPassword = generateRandomPassword(); // Define this function to generate a random password
// Hash the password
$hashedPassword = password_hash($pdfPassword, PASSWORD_BCRYPT);

// Convert the HTML file to PDF using Python
$htmlFilePath = realpath($fileName);
$pdfDirectory = "C:\\xampp\\htdocs\\question";  // Define the directory path
$pdfFileName = "output_$paperIndex.pdf"; // Output PDF file name
$pdfFilePath = $pdfDirectory . "\\" . $pdfFileName;  // Combine directory path and file name

$pythonPath = "C:\\Users\\arise\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
$convertScriptPath = "convert_html_to_pdf.py";

$command = "$pythonPath $convertScriptPath $htmlFilePath $pdfFilePath $pdfPassword"; // Pass the password as an argument to the Python script

// Insert the data into the database
$sql = "INSERT INTO pdf_passwords (pdf_file_name, hashed_password, orgpassword) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $pdfFileName, $hashedPassword, $pdfPassword);
$stmt->execute();
$stmt->close();
exec($command, $output, $returnCode);
echo $pdfPassword;
if ($returnCode === 0) {
    echo "PDF successfully created!";
} else {
    echo "Error creating PDF: " . implode("\n", $output);
    return; // Exit the function if PDF creation fails
}
   
  echo "entered python";

 
}
class MinRequirements {
  public static $minRequirements = [
      'l1' => 1,
      'l2' => 5,
      'l3' => 5,
      'l4' => 4,
      'l5' => 1,
      'l6' => 5
  ];
}

function generatePaper($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $i, $paperIndex, $qcount,$minRequirements,$template)
{
    global $conn;
    

  // Escape and format values for the SQL query
  $subject = mysqli_real_escape_string($conn, $subject);
  $difficulty = mysqli_real_escape_string($conn, $difficulty);
  $regulation = mysqli_real_escape_string($conn, $regulation);
  $department = mysqli_real_escape_string($conn, $department);
  
  // Generate papers based on the selected template
  if ($template == 'Template1') {
    $marks=10;
      generateTemplate1($conn, $regulation, $subject, $difficulty,$noOfPapers,$department,$YearCount,$semester,$type,$paperIndex,$qcount,$minRequirements); 
      // Pass $questions to generateTemplate1
  } elseif ($template == 'Template2') {
      generateTemplate2($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount,$minRequirements);
  } elseif ($template == 'Template3') {
      generateTemplate3($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $paperIndex, $qcount,$minRequirements);
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

      
    $l1 = isset($_POST['l1']) ? intval($_POST['l1']) : MinRequirements::$minRequirements['l1'];
    $l2 = isset($_POST['l2']) ? intval($_POST['l2']) : MinRequirements::$minRequirements['l2'];
    $l3 = isset($_POST['l3']) ? intval($_POST['l3']) : MinRequirements::$minRequirements['l3'];
    $l4 = isset($_POST['l4']) ? intval($_POST['l4']) : MinRequirements::$minRequirements['l4'];
    $l5 = isset($_POST['l5']) ? intval($_POST['l5']) : MinRequirements::$minRequirements['l5'];
    $l6 = isset($_POST['l6']) ? intval($_POST['l6']) : MinRequirements::$minRequirements['l6'];


  
    //regulation,branch,YearCount,semester,type
    for ($i = 1; $i <= $noOfPapers; $i++) {
      $regulation = $_POST['regulation'];
      $paperIndex = time(); // Initialize $paperIndex
      generatePaper($conn, $regulation, $subject, $difficulty, $noOfPapers, $department, $YearCount, $semester, $type, $i, $paperIndex, $qcount,MinRequirements::$minRequirements,$template);
    }
    
}

// Fetch data for the subjects
$sqlSubjects = "SELECT subject_id, subject_name FROM subjects";
$resultSubjects = $conn->query($sqlSubjects);

// Fetch data for the units
$sqlUnits = "SELECT unit_id, unit_value FROM unit";
$resultUnits = $conn->query($sqlUnits);

// Fetch data for the engineering branches
$sqlEngineeringBranch = "SELECT DISTINCT dept_name FROM department";
$resultEngineeringBranch = $conn->query($sqlEngineeringBranch);


// Fetch data for the regulations
$sqlRegulations = "SELECT DISTINCT regulation_name FROM regulations";
$resultRegulations = $conn->query($sqlRegulations);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="generate.css">
  <title>Paper Generator</title>

 
</head>

<body>
    
<div class="banner">
        <div class="navbar">
        <img src="logo.png" alt="Website Logo" style="vertical-align: middle; width: 80px; height: 80px;">
        <h1 style="color: #b37e15; margin-left: 10px; margin-bottom: 0; font-size: 50px;">ADMIN</h1>
            <hr style="margin-top: 0;">

            <ul>
            <li><a href="college.php" target="_self">College</a></li>
                <li><a href="affiliation.php" target="_self">Affiliation</a></li>
                <li><a href="degree.php" target="_self">Degree</a></li>
                <li><a href="department.php" target="_self">Department</a></li>
                <li><a href="subjects.php" target="_self">Subjects</a></li>
                <li><a href="programs.php" target="_self">Program Outcomes</a></li>
                <li><a href="notification.php" target="_self">Notification</a></li>
                <li><a href="regulations.php" target="_self">Regulation</a></li>
                <li><a href="unit.php" target="_self">Unit</a></li>
                <li><a href="generate.php" target="_self">SEM</a></li>
                <li><a href="midgen.php" target="_self">MID</a></li>
                <li><a href="mcq.php" target="_self">MCQ</a></li>
                <li><a href="timetable.php" target="_self">Time Table</a></li>
                <li><a href="logout.php" target="_self">logout</a></li>
            </ul>
       </div>
</div><br>

  <div class="paper-generator">
    
    <form action="#" method="post" id="paperForm">
    <h2>Semester Question Paper Generator</h2>
      <label for="regulation">Regulation:</label>
      <select id="regulation" name="regulation">
      <?php
        // Generate options for regulations
        while ($row = $resultRegulations->fetch_assoc()) {
            echo "<option value='{$row['regulation_name']}'>{$row['regulation_name']}</option>";
        }
        ?>
      </select>

      <label for="branch">Branch:</label>
      <select id="branch" name="branch">
      <?php
        // Generate options for engineering branches
        while ($row = $resultEngineeringBranch->fetch_assoc()) {
            echo "<option value='{$row['dept_id']}'>{$row['dept_name']}</option>";
        }
        ?>
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
        <?php
        // Generate options for subjects
        while ($row = $resultSubjects->fetch_assoc()) {
            echo "<option value='{$row['subject_id']}'>{$row['subject_name']}</option>";
        }
        ?>
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
      <label for="BloomsSelect">Select Blooms Levels:</label>
      <div class="divTable">
          <div class="divTableBody">
            <div class="divTableRow">
              <div class="divTableCell">
                <label for="l1">l1:</label>
                <input type="number" id="l1" name="l1" required value="2"><br>
              </div>
              <div class="divTableCell">
                <label for="l2">l2:</label>
                <input type="number" id="l2" name="l2" required value="3"><br>
              </div>
              <div class="divTableCell">
                <label for="l3">l3:</label>
                <input type="number" id="l3" name="l3" required value="4"><br>
              </div>
            </div>
            <div class="divTableRow">
              <div class="divTableCell">
                <label for="l4">l4:</label>
                <input type="number" id="l4" name="l4" required value="2"><br>
              </div>
              <div class="divTableCell">
                <label for="l5">l5:</label>
                <input type="number" id="l5" name="l5" required value="3"><br>
              </div>
              <div class="divTableCell">
                <label for="l6">l6:</label>
                <input type="number" id="l6" name="l6" required value="3"><br>
              </div>
            </div>
          </div>
        </div>
      
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