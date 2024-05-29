<?php
$server="localhost";
$usm="root";
$pass="";
$db="paper";
$con=mysqli_connect($server,$usm,$pass,$db);
?>
<?php
$password_err = "";
/* Check if the user is already logged in, if yes then redirect him to welcome page */

if(isset($_POST['submit']))
{
	$name=$_POST['name'];
	$pw=$_POST['pw'];
	$qry="select * from userdetails where name ='".$name."' and pw='".$pw."';";
	$run=mysqli_query($con,$qry);
	if($name=="" || $pw=="")
	{
		$password_err= "Please Enter email and password!";
	}
	else
	{
		if(mysqli_num_rows($run)>0)
		{
			if($name==="admin" && $pw==="Naresh@123")
			{
				session_start();
				/* Store data in session variables */
				$_SESSION["loggedin"] = true;
				$_SESSION["id"] = $id;
				$_SESSION["name"] = $name;
				header("location:admin.php");			
			}
			else
			{
				session_start();
				/* Store data in session variables */
				$_SESSION["loggedin"] = true;
				$_SESSION["id"] = $id;
				$_SESSION["name"] = $name;
				header("location:teacher.php");
			}
			mysqli_close($link);
		}
		else
		{
			$password_err = "The credentials you entered was not valid.";
		}
	}	
}
?>

<html>
 <head>
	<title>login for generating paper</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" integrity="sha384-b0f5JzuLB6zaoFjrR6L9JGG5Jv1OVkvmE6gYo46c8Wl4GL0aUxbBhloVqc9ROjIl" crossorigin="anonymous">

	<style>
body {
  background: url('https://img.freepik.com/free-photo/pieces-blue-stationery_23-2148169528.jpg?size=626&ext=jpg&ga=GA1.1.1546980028.1703462400&semt=ais');
  background-size: cover;
  background-position: middle center;
  font-family: 'Courier New', Courier, monospace;
  margin: 0;
  overflow: auto;
  min-height: 100vh;
}

.login {
  width: 420px;
  height: 490px;
  color: #fff;
  top: 35%;
  left: 50%;
  position: absolute;
  transform: translate(-50%, -50%);
  box-sizing: border-box;
  border-radius: 2px;
  background-color: rgba(0, 0, 0, 0.5); /* Decreased opacity */
  padding: 20px;
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
  transition: transform 0.5s ease-in-out;
}

h1 {
  text-align: center;
  color: #fff;
}

.login p {
  font-weight: bold;
  font: white;
}

.login input {
  width: 50%;
  margin-bottom: 20px;
}

.login input[type="text"],
.login input[type="password"] {
  background: transparent;
  color: #fff;
  font-size: 16px;
  height: 30px;
  width: 100%;
  margin-bottom: 10px;
  padding: 10px;
  border: 2px solid #fff; /* Increased border thickness */
  border-radius: 5px;
  box-sizing: border-box;
}

.login input[type="submit"] {
  height: 40px;
  width: 200px;
  background-color: blue;
  border-radius: 50px;
  color: #fff;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login input[type="submit"]:hover {
  background-color: darkblue;
}

.help-block {
  color: blue;
  margin-left: 30px;
}

	</style>
 </head>
 <body>
	  <div class="login">
        <center>
	    <h1>Login Here</h1>
        <hr>
		<form name="myform" target="_self" method="post" action="signin.php">
		  <p>Username</p>
		  <input type="text" name="name" placeholder="  Enter Username">
		  <p>Password</p>
		  <input type="password" name="pw" placeholder="  Enter Password"><br><br>
		  <span class="help-block"><?php echo $password_err; ?></span>
		  <center><input type="submit" name="submit" value="login">
		  <p>Don't have an account <a href="signup.php" target="_self">signup</a></p></center>
		</form>
        </center>
	  </div>
 </body>
</html>