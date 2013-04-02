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
	
	$query = "SELECT DISTINCT String FROM (SELECT DISTINCT date(from_unixtime(rlog.dtmRideDate)) AS tripdate, rlog.dtmRideDate AS ridedate, pl.chrParkName AS String FROM tblRideLog rlog JOIN tblRideList rl ON rlog.intRideID = rl.idsRide JOIN tblParkList pl on rl.intParkID = pl.idsPark WHERE rlog.intUserID = $uid AND rlog.ysnInvalidateRide = 0) AS trips WHERE tripdate = '$date' ORDER BY ridedate ASC";
	data_list($query, NULL, 1);
	
	echo '<h2>Rides Ridden</h2>';
	
	$query = "SELECT * FROM (SELECT DISTINCT DATE(from_unixtime(rlog.dtmRideDate)) AS Date, rlog.intRideID AS Value, rlog.dtmRideDate AS RideTime, rl.chrRideName AS String, st.chrName AS Specials FROM tblRideLog rlog JOIN tblRideList rl ON rlog.intRideID = rl.idsRide JOIN tblParkList pl ON rl.intParkID = pl.idsPark LEFT JOIN tblSpecialType st ON rlog.intSpecialID = st.idsSpecialType WHERE rlog.intUserID = $uid) AS Trips WHERE Date = '$date' ORDER BY RideTime ASC";
	if($is_mobile) {
		data_list($query, NULL, 1);
	} else {
		data_list($query, NULL, 0);
	}

	include('includes/footer.php');





