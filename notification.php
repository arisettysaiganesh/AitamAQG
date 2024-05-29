<?php
$server="localhost";
$usm="root";
$pass="";
$db="paper";
$conn=mysqli_connect($server,$usm,$pass,$db);
?>
<?php
   $de_err ="";
   if(isset($_POST["submit"]))
{
    $notification_id=$_POST["notification_id"];
    $notification_type=$_POST["notification_type"];
    $notification_message=$_POST["notification_message"];
    $degree_id=$_POST["degree_id"];
    $regulation_id=$_POST["regulation_id"];
    $semester=$_POST["semester"];
    $year=$_POST["year"];
    $table_id=$_POST["table_id"];
    if( $notification_id==""||$notification_type==""||$notification_message==""||$degree_id==""||$regulation_id=="" ||$semester==""||$year==""||$table_id=="")
    {
        $de_err = "Please enter all credentials";			
    }
    else
    {
        $query="Insert into notification_table values('$notification_id','$notification_type','$notification_message','$degree_id','$regulation_id','$semester','$year','$table_id');";
        $run=mysqli_query($conn,$query);
        if($run)
        {
            $de_err = "Done";
        }
        else
        {
            $de_err = "details not filled completely";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTIFICATION Form</title>
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

.notification-form {
    background-color: #fff;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 500px;
    margin: 50px auto;
}

.notification-form label{
    display: block;
    margin-bottom: 8px;
    color: #333;
}

.notification-form input{
    width: 100%;
    padding: 8px;
    margin-bottom: 16px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="radio"] {
    display: none; /* Hide the actual radio button */
}

/* Style for each label */
label[for^="regRadio"],
label[for^="supRadio"],
label[for^="sem1"],
label[for^="sem2"],
label[for^="year1"],
label[for^="year2"],
label[for^="year3"],
label[for^="year4"]{
    display: block;
    padding: 5px;
    text-align: center;
    background-color: #e0e0e0; /* Background color, adjust as needed */
    cursor: pointer;
}

/* Style for the checked radio button label */
input[type="radio"]:checked + label {
    background-color: #4caf50; /* Change the background color when checked, adjust as needed */
    color: #ffffff; /* Change the text color when checked, adjust as needed */
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
<div class="notification-form">
    <form>
        <label for="notification_id">Notification Id:</label>
        <input type="text" id="notification_id" name="notification_id" placeholder="Enter Notification ID" required>

        <label for="notification_type">Notification Type:</label>
        <input type="radio" name="notification_type" value="Regular" id="regRadio">
        <label for="regRadio">REGULAR</label>

        <input type="radio" name="notification_type" value="Supplimentary" id="supRadio">
        <label for="supRadio">SUPPLIMENTARY</label>

         <br>
         <br>

         <label for="notification_message">Notification Message:</label>
         <input type="text" id="notification_message" name="notification_message" placeholder="Enter Notification Message" required>

        <label for="degree_id">Degree Id:</label>
        <input type="text" id="id" name="degree_id" placeholder="Enter Degree ID" required>

        <label for="regulation_id">Regulation Id:</label>
        <input type="text" id="regulation_id" name="regulation_id" placeholder="Enter Regulation_id ID" required>

         <label for="semester">Semester:</label>
        <input type="radio" name="semester" value="I" id="sem1">
        <label for="sem1">I</label>
        <input type="radio" name="semester" value="II" id="sem2">
        <label for="sem2">II</label>

        <label for="year">Year:</label>
        <input type="radio" name="year" value="I" id="year1"> 
        <label for="year1">I</label>
        <input type="radio" name="year" value="II" id="year2"> 
        <label for="year2">II</label>
        <input type="radio" name="year" value="I" id="year3"> 
        <label for="year3">III</label>
        <input type="radio" name="year" value="I" id="year4"> 
        <label for="year4">IV</label>

        <label for="table_id">Table Id:</label>
        <input type="text" id="table_id" name="table_id" placeholder="Enter Table ID" required>


        <input type="submit" name="submit" value="submit">
    </form>
</div>
</body>
</html>