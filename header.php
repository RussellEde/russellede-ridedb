<?php
	//RideDB Header Page [header.php]
	echo('<?xml version="1.0"?>');
	require_once('functions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RideDB || russellede.co.uk</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="generator" content="NoteTab Pro 7.1 (www.notetab.com)" />
	<?php
		$currentpage = basename($_SERVER['SCRIPT_NAME']);
		
		if($currentpage == 'addride.php') {
			//Use Ride ID to find Park ID
			$result = mysql_query("SELECT tblParkList.idsPark from tblParkList, tblRideList WHERE tblParkList.idsPark = tblRideList.intParkID AND tblRideList.idsRide = $rideid");
			$row = mysql_fetch_row($result);
			$parkid = $row[0];
		}
		if (isset($ridetaken)) {
			echo("<meta http-equiv=\"refresh\" content=\"2;url=ridelist.php?parkid=$parkid\">");
		}
	?>
</head>

<body>
<div id="container">
	<div id="header"><?php
		if ((isset($rideid)) && ($rideid != 0)) {
			//Use Ride ID to find Park ID
			$result = mysql_query("SELECT tblParkList.chrParkName from tblParkList, tblRideList WHERE tblParkList.idsPark = tblRideList.intParkID AND tblRideList.idsRide = $rideid");
			$row = mysql_fetch_row($result);
			echo($row[0]);
		} elseif ((isset($parkid)) && ($parkid != 0)) {
			$result = mysql_query("SELECT chrParkName from tblParkList WHERE idsPark = $parkid");
			$row = mysql_fetch_row($result);
			echo($row[0]);
		} else {
			echo('Ride DB');
		}
			
	?>
	</div>
	<div id="main">
