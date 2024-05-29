<?php
$server="localhost";
$usm="root";
$pass="";
$db="paper";
$conn=mysqli_connect($server,$usm,$pass,$db);
?>
<?php
	$password_err ="";
    if(isset($_POST["submit"]))
    {
		$title=$_POST["title"];
		$name=$_POST["name"];
		$qual=$_POST["qual"];
		$t_id=$_POST["t_id"];
		$email=$_POST["email"];
		$pw=$_POST["pw"];
		$cpw=$_POST["cpw"];
		if($title=="" ||$name=="" ||$qual=="" ||$t_id=="" ||$email=="" || $pw=="" || $cpw=="")
		{
			$password_err = "Please enter all credentials";			
		}
		else{
		if($pw!=$cpw)
		{
			$password_err = "Passwords did not match.";
		}
		else
		{
			$query="Insert into userdetails values('$title','$name','$qual','$t_id','$email','$pw','$cpw');";
			$run=mysqli_query($conn,$query);
			if($run)
			{
				$password_err = "Account Created";
			}
			else
			{
				$password_err = "form not submitted";
			}
		}
	}
	}
	  ?>
<html>
 <head>
	<title>login for generating question paper</title>
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
  width: 520px;
   /* Set a maximum height based on viewport height */
   min-height: 400px; 
  color: #fff;
  top: 70%;
  left: 50%;
  
  position: absolute;
  transform: translate(-50%, -50%);
  box-sizing: border-box;
  border-radius: 2px;
  background-color: rgba(0, 0, 0, 0.5);
  padding: 40px 20px; /* Add padding to the top */
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
  width: 100%; /* Adjusted width to full width */
  margin-bottom: 20px;
  padding: 10px; /* Added padding */
  box-sizing: border-box;
}

.login input[type="text"],
.login input[type="password"] {
  background: transparent;
  color: #fff;
  font-size: 16px;
  height: 40px;
  border: 2px solid #fff;
  border-radius: 5px;
  box-sizing: border-box;
}

.login input[type="submit"] {
  height: 50px;
  width: 100%; /* Adjusted width to full width */
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
	    <h1>Sign Up Here</h1>
        <hr>
		<form name="myform" target="_self" action="signup.php" method="POST" >
		<table>
		  <p>Title</p>
		  <select name="title" required>
			 <option>Dr.</option>
			 <option>Mr.</option>
			 <option>Mrs.</option>
          </select>
		  <p>Username</p>
		  <input type="text" name="name" placeholder="  Enter Full Name">
		  <p>Qualification</p>
		  <input type="text" name="qual" placeholder="  Enter Qualifcation">
		  <p>Teacher Id</p>
		  <input type="text" name="t_id" placeholder="  Enter Teacher Id">
		  <p>email</p>
		  <input type="text" name="email" placeholder="  Enter email-id">
		  <p>Password</p>
		  <input type="password" name="pw" placeholder="  Enter Password">
		  <p>Confirm Password</p>
		  <input type="password" name="cpw" placeholder=" Re-enter your password">
		  </center>
          <center> <span class="help-block"><?php echo $password_err ?></span></center>
		  <center><input type="submit" name="submit"  value="Sign up">&nbsp;
		 <p>Already have an account!<a href="signin.php">sign in</a></p></center>
        </table>
		</form>
        </center>
	  </div>
 </body>

</html>