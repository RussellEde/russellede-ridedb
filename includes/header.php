<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
	<link rel="stylesheet" href="css/<?php if($is_mobile) { echo('mobile'); } else { echo('desktop'); } ?>.css" type="text/css" media="screen" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<?php
		if ($refresh !== null)
			echo("<meta http-equiv=\"refresh\" content=\"2; url=$refresh\" />");
	?>
</head>
<body>
<div id="container">
	<?php if($is_mobile) { ?><div id="header"><?=$title?></div><?php } else { ?>
	<div id="header">
		<div id="header-left"><a href="index.php"><img alt="<?=$title?>" src="images/logo.png" /></a></div>
		<div id="header-right"><a href="logout.php">Logout of <?=$title?></a></div>
	</div>
	<div id="content">
		<div id="sidebar"><?php include('sidebar.php'); ?></div>
	<?php } ?>
	<div id="main"><?php if(!$is_mobile) { echo("\n\t\t<h1>$pagetitle</h1>"); } ?>