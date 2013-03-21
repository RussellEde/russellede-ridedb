<?php
	//RideDB Trip Module [trip.php]
	require('includes/start.php');
	
	$my = 'My';
		
	$uid = get_int_param('uid');
	if (($uid === NULL) || ($uid == $user->id)) {
		$uid = $user->id;
	} else {
		$result = mysql_query("select username from tblUsers where id = $uid");
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$my = $row['username'].'\'s';
		else
			$uid = $user->id;
	}
	
	$date = get_string_param('date');
	if ($date === null)
		die('No date specified.');
	
	$title = "$my Trip on $date";
	include('includes/header.php');	
	
	echo '<h2>Parks Visited</h2>';
	
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
	
	echo '<h2>Rides Ridden</h2>';
	
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

	include('includes/footer.php');





