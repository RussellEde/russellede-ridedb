<?php
	
	//RideDB Park & Rides Listings Module [ridelist.php]
	require('config.php'); //Require Configuration File
	
	//First, check login
	if(!$user){ 
		header('Location: login.php'); //If not logged in, redirect to login page
		die('You need to log in.'); // prevent further execution
	}
	
	// check for GET params
	$uid = $user->id;
	$my = 'My';
	if (isset($_GET['uid'])) {
		if (is_numeric($_GET['uid'])) {
			$uid = (int)$_GET['uid'];
			$result = mysql_query("select username from tblUsers where id = $uid");
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$my = $row['username'].'\'s';
			} else {
				die('That user ID is invalid.');
			}
		}
	}
	
	include('header.php');	
	echo "<h2>$my Trips</h2>";
	
	$sql = <<<EOF
select distinct
	date(from_unixtime(rlog.dtmRideDate)) as tripdate,
	pl.chrParkName as park
from
	tblRideLog rlog
	join tblRideList rl on rlog.intRideID = rl.idsRide
	join tblParkList pl on rl.intParkID = pl.idsPark
where
	rlog.intUserID = $uid
order by tripdate desc
EOF;

?><ul><?php
	$result = mysql_query($sql);
	$date = '';
	$parks = '';
	while (true) {
		if (!$row = mysql_fetch_array($result, MYSQL_ASSOC))
			break;
		
		if ($row['tripdate'] == $date) {
			$parks .= ', '.$row['park'];
		} else {
			if ($date != '')
				echo "<li><a href=\"trip.php?uid=$uid&amp;date=$date\">$date - $parks</a></li>";
			$date = $row['tripdate'];
			$parks = $row['park'];
		}
	}
	
	if ($date != '')
		echo "<li><a href=\"trip.php?uid=$uid&amp;date=$date\">$date - $parks</a></li>";
?></ul><?php

	//Include footer file
	include('footer.php');





