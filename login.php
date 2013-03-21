<?php 

require("config.php"); 

$msg = $_GET['msg']; 

if($_POST){ 
	//Set up parameters 
	$username = $_POST['username']; 
	$password = $_POST['password']; 
	//Set up redirect link according to $_GET query 'return'. 
	if($_GET['return']){ $redirect = $_GET['return']; } 
	else{ $redirect = "index.php"; } 
	 
	$login = lumos_login($username,$password,$_POST['cookie']); 
	//Redirect after login 
	if($login){ header("Location: $redirect"); } 
	//Create an error message incase login fails 
	else if(!$login){ $msg = "Error! Could not login. Please try again.<br />"; }
}

if(lumos_check_login()){ 
	//Redirect to account page if already logged in 
	header("Location: ridelist.php"); 
}

include("header.php");

?>
<h2>Login</h2> 

<form action="login.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="post"> 
<div id="login"> 
<?php echo $msg; ?><br /> 
Username: <input type="text" name="username" /><br /><br /> 
Password: <input type="password" name="password" /><br /><br /> 
<input type="checkbox" name="cookie" /> Remember Me? 
<input type="submit" value="Submit" /><br /><br /> 
</div> 
</form>
<?php
	include("footer.php");
