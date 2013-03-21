<?php
	//RideDB Add Ride Module [addride.php]
	require('config.php'); //Require Configuration File
	
	//First, check login
	if(!$user){
		header('Location: login.php'); //If not logged in, redirect to login page
		die('You need to log in.'); // prevent further execution
	}
	
	//Check for POST Variables
	if(isset($_GET['rideid'])) {
		$rideid = $_GET['rideid']; //Check to see if a Ride has been selected
		if(!is_numeric($rideid)) {
			header('Location: ridelist.php'); //If illegal input detected, return to Parks & Rides Listings
			die('That ride ID is invalid.'); // prevent further execution
		}
	} else {
			header('Location: ridelist.php'); //If $rideid not set, return to Parks & Rides Listings
			die('That ride ID is invalid.'); // prevent further execution
	}
	if(isset($_GET['ridetaken'])) {
		$ridetaken = $_GET['ridetaken']; //Check to see if a Ride has been taken
		if(!is_numeric($ridetaken)) {
			header('Location: ridelist.php'); //If illegal input detected, return to Parks & Rides Listings
			die('That ride taken is invalid.'); // prevent further execution
		}
	}
	
	//Include header file
	include('header.php');
	
	//If $ridetaken set, then add ride occurence to DB
	if(isset($ridetaken)) {
		$timestamp = time(); //Create timestamp
		$date = date('d-M-y', $timestamp); //Format for display timestamp as dd-MMM-yy
		$time = date('H:i', $timestamp); //Format for display timestamp as hh:mm (24h)
		
		$special = 'null';
		if (isset($_GET['special']))
			if (is_numeric($_GET['special']))
				$special = $_GET['special'];

		mysql_query("INSERT INTO tblRideLog VALUES (NULL, '$rideid', '$timestamp', '$user->id', '0', $special);"); //Insert into DB, autonumber id, $rideid, $timestamp, user id, valid ride, special
		echo("\n\t\t<div id=\"notice\">Added a ride on \""); //Open output Notice Box to state that ride has been added
		$result = mysql_query("SELECT * FROM tblRideList WHERE idsRide = $rideid"); //Retrieve ride information from DB
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($row['ysnTheRide'] == 1) {
				echo('The ');
			}
			echo($row['chrRideName']);
		}
		echo("\" at $time on $date.</div>"); //Output date/time of occurrence and close Notice Box
		
	//If $ridetaken not set, then create form to add ride occurence to DB instead.
	} else {
		$result = mysql_query("select * from tblRideList where idsRide = $rideid");
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			// ride name
			$ridename = $row['chrRideName'];
			if ($row['ysnTheRide']) $ridename = 'The '.$ridename;
			echo("\n\t\t<div id=\"notice\">Confirmation Required</div>\n\t\tDo you really want to add a ride on \"$ridename\"?");
			
			// row of buttons for yes and no
			echo("\n\t\t<div id=\"button-wrapper\">\n\t\t\t<div id=\"button-yes\"><a href=\"addride.php?rideid=$rideid&amp;ridetaken=1\">Yes</a></div>\n\t\t\t<div id=\"button-no\"><a href=\"ridelist.php?parkid=$parkid\">No</a></div>\n\t\t</div>");
			
			
			// create extra buttons for any relevant specials
			$result = mysql_query("select * from tblSpecialType s join tblRideSpecial rs on s.idsSpecialType = rs.intSpecialID where rs.intRideID = $rideid");
			while ($row2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$id = $row2['idsSpecialType'];
				$name = $row2['chrName'];
				$shortname = $row2['chrShortName'];
				echo("\n\t\t<div id=\"button-wrapper\">\n\t\t\t<div id=\"button-$shortname\"><a href=\"addride.php?rideid=$rideid&amp;ridetaken=1&amp;special=$id\">$name</a></div>\n\t\t</div>");
			}
		} else {
			echo 'Couldn\'t get data from the database.';
		}
	}
	
	//Include footer file
	include("footer.php");
	
?>
