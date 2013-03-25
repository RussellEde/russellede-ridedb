<!DOCTYPE html>
<html>
<head>
	<title><?=$title?> || <?=$site_title?></title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<?php
		if ($refresh !== null)
			echo("<meta http-equiv=\"refresh\" content=\"2; url=$refresh\" />");
	?>
</head>
<body>
<div id="container">
	<div id="header"><?=$title?></div>
	<div id="main">
	
