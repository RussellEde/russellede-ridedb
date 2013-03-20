<?php
	//RideDB Add Ride Module [addride.php]
	require("config.php"); //Require Configuration File
	
	//First, check login
	if(!$user){
		header("Location: login.php"); //If not logged in, redirect to login page 
	}
	
	//Check for POST Variables
	if(isset($_GET['rideid'])) {
		$rideid = $_GET['rideid']; //Check to see if a Ride has been selected
		if(!is_numeric($rideid)) {
			header("Location: ridelist.php"); //If illegal input detected, return to Parks & Rides Listings
		}
	} else {
			header("Location: ridelist.php"); //If $rideid not set, return to Parks & Rides Listings
	}
	if(isset($_GET['ridetaken'])) {
		$ridetaken = $_GET['ridetaken']; //Check to see if a Ride has been taken
		if(!is_numeric($ridetaken)) {
			header("Location: ridelist.php"); //If illegal input detected, return to Parks & Rides Listings
		}
	}
	
	//Include header file
	include("header.php");
	
	//If $ridetaken set, then add ride occurence to DB
	if($ridetaken == 1) {
		$timestamp = time(); //Create timestamp
		$date = date("d-M-y", $timestamp); //Format for display timestamp as dd-MMM-yy
		$time = date("H:i", $timestamp); //Format for display timestamp as hh:mm (24h)
		if(isset($_GET['frontrow'])) {
			$frontrowreverse = ", '1', '0'"; //If front-row set, then set $frontrowreverse to front-row 1, reverse 0
		} elseif (isset($_GET['reverse'])) {
			$frontrowreverse = ", '0', '1'"; //Else-if reverse set, then set $frontrowreverse to front-row 0, reverse 1
		} else {
			$frontrowreverse = ", '0', '0'"; //Else set $frontrowreverse to front-row 0, reverse 0
		}
		mysql_query("INSERT INTO tblRideDate VALUES (NULL, '$rideid', '$timestamp', '$user->id', '0'$frontrowreverse);"); //Insert into DB, autonumber id, $rideid, $timestamp, user id, valid ride, $frontrowreverse
		echo("\n\t\t<div id=\"notice\">Added a ride on \""); //Open output Notice Box to state that ride has been added
		$result = mysql_query("SELECT * FROM tblRideList WHERE idsRide = $rideid"); //Retrieve ride information from DB
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($row["ysnTheRide"] == 1) {
				echo("The ");
			}
			echo("$row[chrRideName]");
		}
		echo("\" at $time on $date.</div>"); //Output date/time of occurrence and close Notice Box
		
	//If $ridetaken not set, then create form to add ride occurence to DB instead.
	} else {
		echo("\n\t\t<div id=\"notice\">Confirmation Required</div>\n\t\tDo you really want to add a ride on \""); //Notice Box to advise confirmation required, then ask form question
		$result = mysql_query("SELECT * FROM tblRideList WHERE idsRide = $rideid"); //Retrieve ride information from DB
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($row["ysnTheRide"] == 1) {
				echo("The ");
			}
			echo("$row[chrRideName]");
			
			//Pseudo-form elements
			echo("\"?\n\t\t<div id=\"button-wrapper\">\n\t\t\t<div id=\"button-yes\"><a href=\"addride.php?rideid=$rideid&amp;ridetaken=1\">Yes</a></div>\n\t\t\t<div id=\"button-no\"><a href=\"ridelist.php?parkid=$parkid\">No</a></div>\n\t\t</div>"); //Create row of buttons for Yes / No
			if($row["ysnFrontRow"] == 1) {
				echo("\n\t\t<div id=\"button-wrapper\">\n\t\t\t<div id=\"button-front-row\"><a href=\"addride.php?rideid=$rideid&amp;ridetaken=1&amp;frontrow=1\">Front Row</a></div>\n\t\t</div>"); //Create long button for front-row riding
			}
			if($row["ysnReverse"] == 1) {
				echo("\n\t\t<div id=\"button-wrapper\">\n\t\t\t<div id=\"button-reverse\"><a href=\"addride.php?rideid=$rideid&amp;ridetaken=1&amp;reverse=1\">Reverse</a></div>\n\t\t</div>"); //Create long button for reverse riding
			}
		}
	}
	
	//Include footer file
	include("footer.php");
	
?>