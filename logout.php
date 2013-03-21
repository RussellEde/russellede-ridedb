<?php 


define('NOLOGIN', 1);
require('includes/start.php');
$refresh = "login.php";

$msg = $_GET['msg'];

$title = 'Logout';
include('includes/header.php');

lumos_logout();

?>
<div id="notice"> 
You are now logged out of <?=$title?>.<br />You will now be redirected to the login page.</div> 
</form>
<?php
  include('includes/footer.php');
