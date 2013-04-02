<?php
	//RideDB Park & Rides Listings Module [ridelist.php]
	require('includes/start.php');
	
	$parkid = get_int_param('parkid', null);

	//Set row counter to 1
	$count = 1;
	
	//If no Park selected, list all Parks «In future, add country selection page»
	if($parkid == 0) {
		$pagetitle = 'Theme Park Listings';
		require('includes/header.php');
		echo("\n");
		$query = "SELECT idsPark as Value, chrParkName as String FROM tblParkList ORDER BY chrParkName ASC"; //List all appropriate parks alphabetically
		data_list($query, NULL, 0);
	}
	
	//If a Park selected, list all appropriate Rides
	if($parkid != 0) {
		$pagetitle = 'Ride Listings for ';
		$result = mysql_query("select * from tblParkList where idsPark = $parkid");
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$pagetitle = $pagetitle.$row['chrParkName'];
		else
			die('Invalid park ID');
		
		require('includes/header.php');
		
		echo("\n\t\t<a href=\"ridelist.php\">&laquo; Back to Park List</a>\n"); //Link to Park listings
		$query = "SELECT idsRide as Value, chrRideName as String, ysnTheRide as Prefix FROM tblRideList WHERE intParkID = $parkid AND ysnClosed = 0 ORDER BY chrRideName ASC"; //List all appropriate rides alphabetically
		data_list($query, $parkid, 0);
	}
	
	include('includes/footer.php');
	
