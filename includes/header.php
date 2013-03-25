<?php echo('<?xml version="1.0"?>'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=$title?> || <?=$site_title?></title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<?php
		if ($refresh !== null)
			echo("<meta http-equiv=\"refresh\" content=\"2;url=$refresh\" />");
	?>
</head>
<body>
<div id="container">
	<div id="header"><?=$title?></div>
	<div id="main">
	
