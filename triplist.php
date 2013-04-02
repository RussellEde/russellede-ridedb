<?php
	//RideDB Trip Listings Module [triplist.php]
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
	
	
	$title = "$my Trips";
	include('includes/header.php');

	$query = "SELECT DISTINCT date(from_unixtime(rlog.dtmRideDate)) AS Date, pl.chrParkName as String, COUNT(DISTINCT pl.chrParkName) as Count FROM tblRideLog rlog JOIN tblRideList rl ON rlog.intRideID = rl.idsRide JOIN tblParkList pl ON rl.intParkID = pl.idsPark WHERE rlog.intUserID = $uid AND rlog.ysnInvalidateRide = 0 GROUP BY Date ORDER BY Date ASC";

	data_list($query, NULL, 0);

	//Include footer file
	include('includes/footer.php');





