<?php
	// Ride DB common functions [includes/functions.php]
	
	
	// 
	// functions to obtain get parameters safely
	// 
	
	function get_int_param($param, $default=null) {
		if (isset($_GET[$param]))
			if (is_numeric($_GET[$param]))
				if ($_GET[$param] == (int)$_GET[$param])
					return (int)$_GET[$param];
		
		return $default;
	}
	
	function get_bool_param($param, $default=null) {
		if (isset($_GET[$param]))
			switch ($_GET[$param]) {
				case '1':
				case 'true':
				case 'yes':
					return true;
				case '0':
				case 'false':
				case 'no':
					return false;
			}
		
		return $default;
	}
	
	function get_string_param($param, $default=null) {
		if (isset($_GET[$param]))
			return mysql_real_escape_string($_GET[$param]);
		
		return $default;
	}
	
	// 
	// functions to obtain post parameters safely
	// 
	
	function post_int_param($param, $default=null) {
		if (isset($_POST[$param]))
			if (is_numeric($_POST[$param]))
				if ($_POST[$param] == (int)$_POST[$param])
					return (int)$_POST[$param];
		
		return $default;
	}
	
	function post_bool_param($param, $default=null) {
		if (isset($_POST[$param]))
			switch ($_POST[$param]) {
				case '1':
				case 'true':
				case 'yes':
					return true;
				case '0':
				case 'false':
				case 'no':
					return false;
			}
		
		return $default;
	}
	
	function post_string_param($param, $default=null) {
		if (isset($_POST[$param]))
			return mysql_real_escape_string($_POST[$param]);
		
		return $default;
	}
	
	// 
	// functions to load things from the database
	// 
	
	function data_list($query, $parkid, $nolink) {
		$count = 1;
		global $currentpage;
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		global $uid;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($count == 1) {
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
				if(($currentpage == "trip.php") OR (($currentpage == "ridelist.php") AND (isset($parkid)))) {
					echo("addride.php?rideid=$row[Value]"); //Start a list entry and open link to add ride occurance page
				} elseif($currentpage == "ridelist.php") {
					echo("ridelist.php?parkid=$row[Value]"); //Start a list entry and open link to ride list for that park
				} else {
					$date = $row['Date'];
					echo("trip.php?uid=$uid&amp;date=$date");
				}
				echo('">');
			}
			if((isset($parkid)) AND ($row['Prefix'] == 1)) {
				echo('The '); //Add word 'The' if ride has an article name
			}
			if(isset($date)) {
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
			if($count == $num_rows) {
				echo('.'); //If last-row, then end with a full-stop
			} else {
				echo(','); //Else, end with a comma
			}			
			echo('</li>'); //Close list entry
			$count++; //Increase row counter
		}
		echo("\n\t\t</ol>"); //Close ordered list
	}