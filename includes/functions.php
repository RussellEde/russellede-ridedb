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
	
	function data_table($query, $edit_delete) {
		$result = mysql_query($query);
		$num_fields = mysql_num_fields($result);
		$field_count = 1;
		$num_rows = mysql_num_rows($result);
		$row_count = 1;
		echo("\n\t\t<table>\n\t\t\t<thead>\n\t\t\t\t<tr>");
		while($field_count <= $num_fields) {
			echo("\n\t\t\t\t\t<th class=\"data_table\">");
			$field_number = $field_count - 1;
			$field_table = mysql_field_table($result, $field_number);
			$field_name = mysql_field_name($result, $field_number);
			$field_result = mysql_query("SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_NAME = '$field_table' AND COLUMN_NAME = '$field_name'");
			$field_row = mysql_fetch_row($field_result);
			$field_comment = str_replace("Ride Log ","",$field_row[0]);
			$field_comment = str_replace("Special Ride Type Name","Ride Details",$field_comment);
			echo("$field_comment</th>");
			if($field_count == $num_fields) {
				$field_count = 1;
				break;
			} else {
				$field_count++;
			}
		}
		if($edit_delete == 1) {
			echo("\n\t\t\t\t\t<th class=\"data_table\">Edit / Delete</th>");
		}
		echo("\n\t\t\t</thead>\n\t\t\t<tbody>");
		$row_count = 1;
		while($row = mysql_fetch_array($result, MYSQL_NUM)) {
			echo("\n\t\t\t\t<tr>");
			while($field_count <= $num_fields) {
				$field_number = $field_count-1;
				$field_data = $row[${field_number}];
				$field_type = mysql_field_name($result, $field_number);
				$field_type = substr($field_type,0,3);
				if($field_type == "dtm") { $field_data = date('d-m-y h:i',$field_data); }
				if($field_data == NULL) { $field_data = "N/A"; }
				echo("\n\t\t\t\t\t<td class=\"data_table");
				if(!($row_count & 1)) {
					echo('_r2');
				}
				echo("\">$field_data</td>");
				if($field_count == $num_fields) {
					$field_count = 1;
					break;
				} else {
					$field_count++;
				}
			}
			if($edit_delete == 1) {
				echo("\n\t\t\t\t\t<td class=\"data_table");
				if(!($row_count & 1)) {
					echo('_r2');
				}
				echo('">E / X</td>');
			}
			echo("\n\t\t\t\t</tr>");
			$row_count++;
		}
		echo("\n\t\t\t</tbody>\n\t\t</table>");
	}