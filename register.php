<?php
	//RideDB User Registration Module [register.php]
	define('NOLOGIN', 1);
	require('includes/start.php');
	require_once('includes/recaptchalib.php'); //Open reCAPTCHA Library
	
	$register = get_bool_param('register', false);
	if ($register)
		$refresh = "ridelist.php";
		
	include('includes/header.php');
	if($register) {
		$resp = recaptcha_check_answer ('6LcW9t0SAAAAANFMLSC7ChqnNv8XkVyHTwsQ12Sk', $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			//If CAPTCHA fails verification
			die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")");
		} else {
			//Continue to Register User
			$username = post_string_param('username', null);
			$password = post_string_param('password', null);
			$email = post_string_param('email', null);
			$firstname = post_string_param('firstname', null);
			$familyname = post_string_param('familyname', null);
			$fields = array('email' => $email, 'chrFirstName' => $firstname, 'chrFamilyName' => $familyname);
			lumos_register($username, $password, $fields);
			lumos_login($username, $password);
			echo("\n\t\t<div id=\"notice\">Successfully Registered as an User.<br />You will now be logged in and redirected to the site.</div>\n");
		}
	} else {
	?>
	<form method="post" action="register.php?register=1">
		<table>
			<tr>
				<td>User Name:</td><td><input maxlength="50" name="username" type="text" /></td>
			</tr><tr>
				<td>Password:</td><td><input maxlength="50" name="password" type="password" /></td>
			</tr><tr>
				<td>First Name:</td><td><input maxlength="50" name="firstname" type="text" /><td>
			</tr><tr>
				<td>Family Name:</td><td><input maxlength="50" name="familyname" type="text" /></td>
			</tr><tr>
				<td>EMail Address:</td><td><input maxlength="50" name="email" type="email" /></td>
			</tr><tr>
				<td colspan="2"><?php echo recaptcha_get_html('6LcW9t0SAAAAALcG-IuFX7Akufnf2aRoCM76AuxV'); ?></td>
			</tr><tr>
				<td colspan="2"><input type="submit" value="Register Account" /></td>
			</tr>
		</table>
	</form>
	<?php
	}
	$showlogout = false;
	include('includes/footer.php');