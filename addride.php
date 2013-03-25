<?php
	//RideDB Add Ride Module [addride.php]
	require('includes/start.php');
	
	$rideid = post_int_param('rideid', null);
	if ($rideid === null)
		$rideid = get_int_param('rideid', null);
	$ridetaken = post_bool_param('ridetaken', false);
	
	$sql = "select pl.idsPark, pl.chrParkName from tblParkList pl join tblRideList rl on pl.idsPark = rl.intParkID where rl.idsRide = $rideid";
	$result = mysql_query($sql);
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$parkid = $row['idsPark'];
		$title = $row['chrParkName'];
	}
	
	if ($ridetaken) {
		// form has been submitted; find out what the name of the button was
		foreach (array_keys($_POST) as $key) {
			if (substr($key, 0, 6) == 'button')
				$tmp_arr = explode('-', $key, 2);
				if (isset($tmp_arr))
					$btn_name = $tmp_arr[1];
		}
		
		//die($btn_name);

		// we didn't take the ride; go back to the ride list
		if ($btn_name == 'no') {
			header("Location: ridelist.php?parkid=$parkid");
			die('Ride not taken; do back to ride list.');
		}
		
		// we did take the ride; we'll want an auto-refresh after we display the message
		$refresh = "ridelist.php?parkid=$parkid";
	}
	
	include('includes/header.php');
	
	//If $ridetaken set, then add ride occurence to DB
	if ($ridetaken) {
		// get the time that the ride was taken from the form
		$timestamp = strtotime($_POST['timestamp']);		
		if ($timestamp === false)
			$timestamp = time(); // default to now
		
		$date = date('d M y', $timestamp); //Format for display timestamp as dd-MMM-yy
		$time = date('H:i', $timestamp); //Format for display timestamp as hh:mm (24h)
		
		// if we submitted with button-yes, then it's not a special
		// otherwise, the special ID is on the end of the button's name
		// (and is now called $btn_name - see above)
		
		$special = 'null';
		if ($btn_name != 'yes') {
			$special = ''.(int)$btn_name;	// cast to int and back again to ensure it's safe
			
			$sql = "select chrName from tblSpecialType where idsSpecialType = $special";
			$result = mysql_query($sql);
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$special_name = $row['chrName'];
			} else {
				die ('That special type doesn\'t exist!');
			}
		}
		
		$sql = "insert into tblRideLog values (NULL, '$rideid', '$timestamp', '$user->id', '0', $special)";
		//die($sql);
		if (mysql_query($sql)) {
			// added successfully; get ride information to display
			$result = mysql_query("SELECT * FROM tblRideList WHERE idsRide = $rideid");
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$ridename = $row['chrRideName'];
				if ($row['ysnTheRide'] == 1)
					$ridename = 'The '.$ridename;
				if (isset($special_name))
					$ridename .= " ($special_name)";
			}
		}
		
		echo("\n\t\t<div id=\"notice\">Added a ride on \"$ridename\" at $time on $date.</div>");
	} else {
		//If $ridetaken not set, then create form to add ride occurence to DB instead.
		$result = mysql_query("select * from tblRideList where idsRide = $rideid");
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			// ride name
			$ridename = $row['chrRideName'];
			if ($row['ysnTheRide']) $ridename = 'The '.$ridename;
			
			$timestamp = date('Y-m-d H:i:s');
?>
		<div id="notice">Confirmation Required</div>
		<div id="form">
			<form action="addride.php" method="post">
				<!-- First, list all hidden form elements -->
				<input type="hidden" name="parkid" value="<?=$parkid?>" />
				<input type="hidden" name="rideid" value="<?=$rideid?>" />
				<input type="hidden" name="ridetaken" value="true" />
				<!-- Next, create a table to correctly position form elements -->
				<table>
					<!-- Question is the form's context, so Table Head element -->
					<thead>
						<tr>
							<th class="form_question" colspan="2">Do you really want to add a ride on "<?=$ridename?>?"</th>
						</tr>
					</thead>
					<!-- Buttons are always at the bottom of the form, so Table Foot element -->
					<tfoot>
						<tr>
							<td class="form_left"><input type="submit" name="button-yes" id="button-yes" value="Yes" /></td>
							<td class="form_right"><input type="submit" name="button-no" id="button-no" value="No" /></td>
						</tr>
						<?php
							//Create Extra Buttons for any Relevant Specials
							$result = mysql_query("select * from tblSpecialType s join tblRideSpecial rs on s.idsSpecialType = rs.intSpecialID where rs.intRideID = $rideid");
							while ($row2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
								$id = $row2['idsSpecialType'];
								$name = $row2['chrName'];
								$shortname = $row2['chrShortName'];
								echo("\n\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t<td colspan=\"2\"><input type=\"submit\" name=\"button-$id\" id=\"button-$shortname\" value=\"$name\" />\n\t\t\t\t\t\t</tr>");
							}
						?>
					</tfoot>
					<!-- User Input is the body of the form, so Table Body element -->
					<tbody>
						<tr>
							<td class="form_left"><label for="timestamp">Time Ride Taken:</label></td>
							<td class="form_right"><input class="form_input_box" type="text" name="timestamp" value="<?=$timestamp?>" /></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
<?php
		}
	}
	
	$showlogout = false;
	include('includes/footer.php');
	
