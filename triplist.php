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
	include('includes/footer.php');





