
<?php
/* Initialize the session */
session_start();
 
/* Check if the user is logged in, if not then redirect him to login page */
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: signin.php");
    exit;
}
?>
 
<html>
    <head>
     <title>Welcome user!</title>
    <style>
      /*  
      <link rel="stylesheet" href="teach.css">
      .home{
       margin-top:-600px;
       margin-left:60px;
       padding:10px;
       height:75%;
       width:38%; 
       border:2px solid #916e43;
    }
    h3,h4{
    color:#b37e15;*/

    /* Reset some default styles */
    body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to right, #6bb9f0, #3498db);
    margin: 0;
    padding: 0;
    overflow: auto;
}

.banner {
    background-color: #333333;
    color: #ffffff;
    text-align: center;
    padding: 10px;
    margin-right:10px;
}

.navbar {
    background-color: #2c3e50;
    padding: 10px;
    text-align: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    margin-right:10px;
}

.navbar h1 {
    color: #3498db;
    margin-left: 30px;
}

.navbar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

.navbar ul li {
    display: inline;
    margin-right: 20px;
    position: relative;
}

.navbar a {
    text-decoration: none;
    color: #ffffff;
    font-weight: bold;
    padding: 10px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.navbar a:hover {
    background-color: rgba(255, 255, 255, 0.2);
    color: #3498db;
}

.home {
    margin-top: 130px;
    margin-left: auto;
    margin-right: auto;
    padding: 20px;
    width: 40%;
    border: 2px solid #3498db;
    background-color: #ecf0f1;
    animation: subtleAnimation 10s infinite alternate;
    border-radius:30px;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
    font-size:16px;
}

@keyframes subtleAnimation {
    from {
        background-position: 0% 0%;
    }
    to {
        background-position: 100% 100%;
    }
}

h3, h4 {
    color: black;
    /*font-size:20px;*/
}
.home{
    width:50%;
}
.home p {
    line-height: 1.6;
    color: #333333;
}

.home a {
    color: #e74c3c;
}

.home a:hover {
    text-decoration: underline;
}

/* Live background animation */
body::after {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('');
    opacity: 0.8;
    pointer-events: none;
}

    </head>
    <style>
    .home{
       margin-top:-600px;
       margin-left:60px;
       padding:10px;
       height:75%;
       width:38%; 
       border:2px solid #916e43;
    }
    h3,h4{
    color:#b37e15;
}

        </style>
        </head>
    <body>
       <div class="banner">
        <div class="navbar">

        <h1 style="color:#b37e15;margin-left:30px">ADMIN<hr></h1>

            <ul>
              
                
                <li><a href="college.php" target="_self">College</a></li>
                <li><a href="affiliation.php" target="_self">Affiliation</a></li>
                <li><a href="degree.php" target="_self">Degree</a></li>
                <li><a href="department.php" target="_self">Department</a></li>
                <li><a href="subjects.php" target="_self">Subjects</a></li>
                <li><a href="programs.php" target="_self">Program Outcomes</a></li>
                <li><a href="notification.php" target="_self">Notification</a></li>
                <li><a href="regulations.php" target="_self">Regulation</a></li>
                
                <li><a href="timetable.php" target="_self">Time Table</a></li>
                <li><a href="logout.php" target="_self">logout</a></li>

                
                <li><a href="generatetemp.php" target="_self">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Generate Question Paper</a></li>
            </ul>
       </div>
       <br>
</div>
       <div class="home">
        <p align="justify" line-height="1.6">
        &nbsp;&nbsp;&nbsp;&nbsp; 
<center><h2>Question Paper Generation</h2>
        <h4>Diversity and Fairness:</h4>  
        <p>
         &nbsp;&nbsp;&nbsp;&nbsp;By using question paper generation systems, educators can ensure that each version of the exam is unique. This helps in preventing cheating and promotes fairness in the assessment process.</p>
        <h4>Time Efficiency:</h4>
        <p> 
        &nbsp;&nbsp;&nbsp;&nbsp; Manually creating question papers can be time-consuming. Automated question paper generation systems can significantly reduce the time and effort required to create exams.</p>
        <br><h4>Security and Integrity:</h4>
        <p>
        &nbsp;&nbsp;&nbsp;&nbsp;Question paper generation systems can enhance the security and integrity of exams. They can be designed to prevent unauthorized access, and some systems may even include features to encrypt or secure the question papers until the scheduled exam time.</p>  
</center>
    </div>
</div>
</body>
</html>