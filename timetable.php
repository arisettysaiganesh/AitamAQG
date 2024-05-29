<?php
$server="localhost";
$usm="root";
$pass="";
$db="paper";
$conn=mysqli_connect($server,$usm,$pass,$db);
?>
<?php
   $t_err ="";
   if(isset($_POST["submit"]))
{
    $table_id=$_POST["table_id"];
    $date=$_POST["date"];
    $day=$_POST["day"];
    $branch=$_POST["branch"];
    $timings=$_POST["timings"];
    $copy_to=$_POST["copy_to"];
    if($table_id==""||$date=="" ||$day==""||$branch==""||$timings==""||$copy_to=="")
    {
        $t_err = "Please enter all credentials";			
    }
    else
    {
        $query="Insert into table_data values('$table_id','$date','$day','$branch','$timings','$copy_to');";
        $run=mysqli_query($conn,$query);
        if($run)
        {
            $t_err = "Done";
        }
        else
        {
            $t_err = "details not filled completely";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TIMETABLE Form</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.banner {
    background-color: #333;
    color: #fff;
    padding: 10px;
    text-align: center;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 40px;
}

.navbar h1 {
    margin: 0;
}

.navbar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.navbar li {
    margin-right: 20px;
}

.navbar a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
}

.navbar a:hover {
    color: #b37e15;
}

.table-form {
    background-color: #fff;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 500px;
    margin: 50px auto;
}

.table-form label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

.table-form input {
    width: 100%;
    padding: 8px;
    margin-bottom: 16px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    background-color: #4caf50;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

    </style>
</head>
<body>
<div class="banner">
        <div class="navbar">

        <h1 style="color:#b37e15;margin-left:40px">ADMIN<hr></h1>

            <ul>
            <li><a href="college.php" target="_self">College</a></li>
                <li><a href="affiliation.php" target="_self">Affiliation</a></li>
                <li><a href="degree.php" target="_self">Degree</a></li>
                <li><a href="department.php" target="_self">Department</a></li>
                <li><a href="subjects.php" target="_self">Subjects</a></li>
                <li><a href="programs.php" target="_self">Program Outcomes</a></li>
                <li><a href="notification.php" target="_self">Notification</a></li>
                <li><a href="regulations.php" target="_self">Regulation</a></li>
                <li><a href="generate.php" target="_self">generate</a></li>
                <li><a href="timetable.php" target="_self">Time Table</a></li>
                <li><a href="logout.php" target="_self">logout</a></li>
            </ul>
       </div>
</div>
       <br>
<div class="table-form">
    <form>
            <label for="table_id">Table ID:</label>
            <input type="text" id="table_id" name="table_id" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="day">Day:</label>
            <input type="text" id="day" name="day" required>

            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" required>

            <label for="timings">Timings:</label>
            <input type="time" id="timings" name="timings" required>

            <label for="copy_to">Copy To:</label>
            <input type="text" id="copy_to" name="copy_to" required>

           <input type="submit" name="submit" value="submit">

    </form>
</div>
</body>
</html>