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
	// functions to load things from the database
	// 
	
	function load_special_types() {
		$specials = array();
		$result = mysql_query('select * from tblSpecialType');
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$specials[$row['idsSpecialType']] = $row['chrName'];
		}
		return $specials;
	}
	
	
	
	
	
	
	
	
