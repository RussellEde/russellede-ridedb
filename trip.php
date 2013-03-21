<?php
	
	//RideDB Trip Module [trip.php]
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
			if ($uid != $user->id) {
				$result = mysql_query("select username from tblUsers where id = $uid");
				if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$my = $row['username'].'\'s';
				} else {
					die('That user ID is invalid.');
				}
			}
		}
	}
	
	if (isset($_GET['date'])) {
		$date = mysql_real_escape_string($_GET['date']);
	} else {
		die('No date specified.');
	}
	
	include('header.php');	
	echo "<h2>$my Trip on $date</h2>";
	
	echo '<h3>Parks Visited</h3>';
	
	$sql = <<<EOF
select distinct park from (
	select distinct
		date(from_unixtime(rlog.dtmRideDate)) as tripdate,
		rlog.dtmRideDate as ridedate,
		pl.chrParkName as park
	from
		tblRideLog rlog
		join tblRideList rl on rlog.intRideID = rl.idsRide
		join tblParkList pl on rl.intParkID = pl.idsPark
	where
		rlog.intUserID = $uid
) as trips
where
	tripdate = '$date'
order by ridedate asc
EOF;

?><ul><?php

	$result = mysql_query($sql);
	echo mysql_error();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$park = $row['park'];
		echo "<li>$park</li>";
	}
	
?></ul><?php
	
	echo '<h3>Rides Ridden</h3>';
	
	$specials = load_special_types();
	
	$sql = <<<EOF
select * from (
	select distinct
		date(from_unixtime(rlog.dtmRideDate)) as tripdate,
		rlog.dtmRideDate as ridedate,
		rl.chrRideName as ride,
		rlog.intSpecialID as special
	from
		tblRideLog rlog
		join tblRideList rl on rlog.intRideID = rl.idsRide
		join tblParkList pl on rl.intParkID = pl.idsPark
	where
		rlog.intUserID = $uid
) as trips
where
	tripdate = '$date'
order by ridedate asc
EOF;

?><ul><?php

	$result = mysql_query($sql);
	echo mysql_error();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ride = $row['ride'];
		if (isset($row['special']))
			$ride .= ' ('.$specials[$row['special']].')';
		echo "<li>$ride</li>";
	}
	
?></ul><?php

	//Include footer file
	include('footer.php');





