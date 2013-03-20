<?php
	require('config.php');
	if($user) {
		header('Location: ridelist.php');
	} else {
		header('Location: login.php');
	}
?>
