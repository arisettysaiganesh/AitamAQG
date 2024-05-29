<?php
$server="localhost";
$usm="root";
$pass="";
$db="paper";
$conn=mysqli_connect($server,$usm,$pass,$db);
?>
<?php
$a_err ="";
if(isset($_POST["submit"]))
{
    $affiliation_id=$_POST["affiliation_id"];
    $university_id=$_POST["university_id"];
    $university_name=$_POST["university_name"];
    $university_details=$_POST["university_details"];
    $college_id=$_POST["college_id"];
    if($affiliation_id==""||$university_id=="" ||$university_name==""||$university_details==""||$college_id=="")
    {
        $a_err = "Please enter all credentials";			
    }
    else
    {
        $query="Insert into affiliation values('$affiliation_id','$university_id','$university_name','$university_details','$college_id')";
        $run=mysqli_query($conn,$query);
        if($run)
        {
            $a_err = "Done";
        }
        else
        {
            $a_err = "details not filled completely";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AFFILIATION Form</title>
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

.affiliation-form {
    background-color: #fff;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 500px;
    margin: 50px auto;
}

.affiliation-form label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

.affiliation-form input {
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
<div class="affiliation-form">
    <form action="affiliation.php" method="POST">
        <label for="affiliation_id">Affiliation Id:</label>
        <input type="text" id="affiliation_id" name="affiliation_id" placeholder="Enter Affiliation ID">

        <label for="university_id">University Id:</label>
        <input type="text" id="university_id" name="university_id" placeholder="Enter University ID">

        <label for="university_name">University Name:</label>
        <input type="text" id="university_name" name="university_name" placeholder="Enter University Name">

        <label for="details">Details:</label>
        <input type="text" id="university_details" name="university_details" placeholder="Enter University Details">

        <label for="college_id">College Id:</label>
        <input type="text" id="college_id" name="college_id" placeholder="Enter College ID">
        
        <input type="submit" name="submit" value="submit">

    </form>
</div>
</body>
</html>