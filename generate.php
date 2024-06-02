<?php
   

// Fetch data for the regulations
$sqlRegulations = "SELECT DISTINCT regulation_name,regulation_id FROM regulations";
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