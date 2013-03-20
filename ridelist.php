<?php
	
	//RideDB Park & Rides Listings Module [ridelist.php]
	require("config.php"); //Require Configuration File
	
	//First, check login
	if(!$user){ 
		header("Location: login.php"); //If not logged in, redirect to login page 
	}
	
	//Check for POST Variables
	if(isset($_GET['parkid'])) {
		$parkid = $_GET['parkid']; //Check to see if a Park has been selected
		if(!is_numeric($parkid)) {
			$parkid = 0; //If illegal input detected, set $parkid to 0
		}
	} else {
		$parkid = 0; //If no Park selected, set $parkid to 0
	}
	if(isset($_GET['rideid'])) {
		$rideid = $_GET['rideid']; //Check to see if a Ride has been selected
		if(!is_numeric($rideid)) {
			$rideid = 0; //If illegal input detected, set $rideid to 0
		}
	} else {
		$rideid = 0; //If no Ride selected, set $rideid to 0
	}
	
	//Include header file
	include("header.php");
	
	//Set row counter to 1
	$count = 1;
	
	//If no Park selected, list all Parks «In future, add country selection page»
	if($parkid == 0) {
		echo("\n");
		$result = mysql_query("SELECT * FROM tblParkList ORDER BY chrParkName ASC"); //List all appropriate parks alphabetically
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($count == 1) {
				echo("\t\t<ol>"); //If first-row, then start an ordered list
			}
			echo("\n\t\t\t<li><a href=\"ridelist.php?parkid=$row[idsPark]\">$row[chrParkName]</a>"); //Start a list entry and return park name (link to ride listing for park)
			if($count == $num_rows) {
				echo("."); //If last-row, then end with a full-stop
			} else {
				echo(","); //Else, end with a comma
			}
			echo("</li>"); //Close list entry
			$count++; //Increase row counter
		}
		echo("\n\t\t</ol>"); //Close ordered list
	}
	
	//If a Park selected, list all appropriate Rides
	if($parkid != 0) {
		echo("\n\t\t<a href=\"ridelist.php\">&laquo; Back to Park List</a>\n"); //Link to Park listings
		$result = mysql_query("SELECT * FROM tblRideList WHERE intParkID = $parkid AND ysnClosed = 0 ORDER BY chrRideName ASC"); //List all appropriate rides alphabetically
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($count == 1) {
				echo("\t\t<ol>"); //If first-row, then start an ordered list
			}
			echo("\n\t\t\t<li>");
			//Check if Ride has been ridden today
			$start_of_day = strtotime("midnight");
			$check_ride_query = mysql_query("SELECT dtmRideDate FROM tblRideDate WHERE dtmRideDate > $start_of_day AND intRideID = $row[idsRide] AND intUserID = $user->id");
			$check_ride_number = mysql_num_rows($check_ride_query);
			if($check_ride_number != 0) {
				echo("<img alt=\"Y\" src=\"images/tick.png\" /> ");
			}
			echo("<a href=\"addride.php?rideid=$row[idsRide]\">"); //Start a list entry and open link to add ride occurance page
			if($row["ysnTheRide"] == 1) {
				echo("The "); //Add word 'The' if ride has an article name
			}
			echo("$row[chrRideName]</a>"); //Return ride name
			if($count == $num_rows) {
				echo("."); //If last-row, then end with a full-stop
			} else {
				echo(","); //Else, end with a comma
			}			
			echo("</li>"); //Close list entry
			$count++; //Increase row counter
		}
		echo("\n\t\t</ol>"); //Close ordered list
	}
	
	//Include footer file
	include("footer.php");
	
?>