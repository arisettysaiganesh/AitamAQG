<?php
$server="localhost";
$usm="root";
$pass="";
$db="paper";
$conn=mysqli_connect($server,$usm,$pass,$db);
?>
<?php
   $s_err ="";
   if(isset($_POST["submit"]))
{
    $id=$_POST["id"];
    $name=$_POST["name"];
    $dept_id=$_POST["dept_id"];
    $regulation_id=$_POST["regulation_id"];
    if($id==""||$name=="" ||$dept_id=="")
    {
        $s_err = "Please enter all credentials";			
    }
    else
    {
        $query="Insert into subjects values('$id','$name','$dept_id','$regulation_id');";
        $run=mysqli_query($conn,$query);
        if($run)
        {
            $s_err = "Done";
        }
        else
        {
            $s_err = "details not filled completely";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUBJECT Form</title>
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

.degree-form {
    background-color: #fff;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 500px;
    margin: 50px auto;
}

.degree-form label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

.degree-form input {
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
<div class="degree-form">
    <form action="subjects.php" method="post" >
        <label for="id">Subject Id:</label>
        <input type="text" id="id" name="id" placeholder="Enter Subject ID" required>

        <label for="name">Subject Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter Subject Name" required>

        <label for="dept_id">Dept Id:</label>
        <input type="text" id="dept_id" name="dept_id" placeholder="Enter Department ID" required>

        <label for="regulation_id">Regulation Id:</label>
        <input type="text" id="regulation_id" name="regulation_id" placeholder="Enter Regulation ID" required>


        <input type="submit" name="submit" value="submit">
    </form>
</div>
</body>
</html>