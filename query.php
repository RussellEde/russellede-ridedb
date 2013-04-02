<?php
	//RideDB Query Module [query.php]
	require('includes/start.php');
	
	//Set Initial Variables
	$parkid = get_int_param('parkid', null);
	$triplist = get_int_param('triplist', null);
	$nolink = 0;
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
	//if ($date === null) { die('No date specified.'); }
	
	//Query Function
	function data_list($query, $parkid, $nolink) {
		$row_count = 1;
		global $currentpage;
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		global $date;
		global $triplist;
		global $uid;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($row_count == 1) {
				echo("\t\t<ol>"); //If first-row, then start an ordered list
			}
			echo("\n\t\t\t<li>");
			//Check if Ride has been ridden today
			if(isset($parkid)) {
				$start_of_day = strtotime('midnight');
				$check_ride_query = mysql_query("SELECT dtmRideDate FROM tblRideLog WHERE dtmRideDate > $start_of_day AND intRideID = $row[idsRide] AND intUserID = $user->id");
				$check_ride_number = mysql_num_rows($check_ride_query);
				if($check_ride_number != 0) {
					?><img alt="Y" src="images/tick.png" /> <?php
				}
			}
			if($nolink == 0) {
				echo('<a href="');
				if($triplist == 1) {
					$date = $row['Date'];
					echo("query.php?uid=$uid&amp;date=$date");
				} elseif((isset($date)) OR (isset($parkid))) {
					echo("addride.php?rideid=$row[Value]"); //Start a list entry and open link to add ride occurance page
				} else {
					echo("query.php?parkid=$row[Value]"); //Start a list entry and open link to ride list for that park
				}
				echo('">');
			}
			if((isset($parkid)) AND ($row['Prefix'] == 1)) {
				echo('The '); //Add word 'The' if ride has an article name
			}
			if($triplist == 1) {
				$textdate = strtotime($date);
				$textdate = date('d-m-y', $textdate);
				echo("$textdate: ");
			}
			if(isset($row['RideTime'])) {
				$ridetime = date('H:i',$row['RideTime']);
				echo("$ridetime: ");
			}
			echo("$row[String]"); //Return String Value
			if((isset($row['Count'])) AND ($row['Count'] > 1)) {
				$extraparks = $row['Count']-1;
				echo(" (+$extraparks Others)");
			} elseif (isset($row['Specials'])) {
				echo(" ($row[Specials])");
			}
			if($nolink == 0) {
				echo('</a>');
			}
			if($row_count == $num_rows) {
				echo('.'); //If last-row, then end with a full-stop
			} else {
				echo(','); //Else, end with a comma
			}			
			echo('</li>'); //Close list entry
			$row_count++; //Increase row counter
		}
		echo("\n\t\t</ol>"); //Close ordered list
	}

	if(!is_null($date)) {
	//If $_GET[date] is set, then show a listing for that trip
		$pagetitle = "$my Trip on $date";
		$section_title = 'Parks Visited';
		$query = "SELECT DISTINCT String FROM (SELECT DISTINCT date(from_unixtime(rlog.dtmRideDate)) AS tripdate, rlog.dtmRideDate AS ridedate, pl.chrParkName AS String FROM tblRideLog rlog JOIN tblRideList rl ON rlog.intRideID = rl.idsRide JOIN tblParkList pl on rl.intParkID = pl.idsPark WHERE rlog.intUserID = $uid AND rlog.ysnInvalidateRide = 0) AS trips WHERE tripdate = '$date' ORDER BY ridedate ASC";
		$nolink = 1;
		$section_title = array($section_title, "Rides Ridden");
		$query = array($query,"SELECT * FROM (SELECT DISTINCT DATE(from_unixtime(rlog.dtmRideDate)) AS Date, rlog.intRideID AS Value, rlog.dtmRideDate AS RideTime, rl.chrRideName AS String, st.chrName AS Specials FROM tblRideLog rlog JOIN tblRideList rl ON rlog.intRideID = rl.idsRide JOIN tblParkList pl ON rl.intParkID = pl.idsPark LEFT JOIN tblSpecialType st ON rlog.intSpecialID = st.idsSpecialType WHERE rlog.intUserID = $uid) AS Trips WHERE Date = '$date' ORDER BY RideTime ASC");
		if($is_mobile) {
			$nolink = array($nolink, 1);
		} else {
			$nolink = array($nolink, 0);
		}
	} elseif($triplist == 1) {
	//Lists all trips by an user
		$pagetitle = "$my Trips";
		$query = "SELECT DISTINCT date(from_unixtime(rlog.dtmRideDate)) AS Date, pl.chrParkName as String, COUNT(DISTINCT pl.chrParkName) as Count FROM tblRideLog rlog JOIN tblRideList rl ON rlog.intRideID = rl.idsRide JOIN tblParkList pl ON rl.intParkID = pl.idsPark WHERE rlog.intUserID = $uid AND rlog.ysnInvalidateRide = 0 GROUP BY Date ORDER BY Date ASC";
	} elseif(!is_null($parkid)) {
	//If a Park selected, create a Ridelist
		$pagetitle = 'Ride Listings for ';
		$result = mysql_query("SELECT chrParkName FROM tblParkList WHERE idsPark = $parkid");
		if ($row = mysql_fetch_row($result)) {
			$pagetitle = $pagetitle.$row[0];
		} else {
			die('Invalid Park ID');
		}
		echo("\n\t\t<a href=\"ridelist.php\">&laquo; Back to Park List</a>\n"); //Link to Park listings
		$query = "SELECT idsRide as Value, chrRideName as String, ysnTheRide as Prefix FROM tblRideList WHERE intParkID = $parkid AND ysnClosed = 0 ORDER BY chrRideName ASC"; //List all appropriate rides alphabetically
	} else {
	//ELSE DISPLAY DEFAULT: If no Park selected, list all Parks «In future, add country selection page»
		$pagetitle = 'Theme Park Listings';
		$query = "SELECT idsPark as Value, chrParkName as String FROM tblParkList ORDER BY chrParkName ASC"; //List all appropriate parks alphabetically
	}
	require('includes/header.php');
	if(isset($first_list)) { echo("$first_list"); }
	if(is_array($query)) {
		$row_count = 0;
		$num_rows = count($query);
		$num_rows-1;
		while($row_count < $num_rows) {
			echo('<h2>');
			echo($section_title[${row_count}]);
			echo('</h2>');
			data_list($query[${row_count}], $parkid, $nolink[${row_count}]);
			$row_count++;
		}
	} else {
		data_list($query, $parkid, $nolink);
	}
	include('includes/footer.php');