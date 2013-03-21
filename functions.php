<?php
	//RideDB Site-Wide Functions [functions.php]

	function load_special_types() {
		$specials = array();
		$result = mysql_query('select * from tblSpecialType');
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$specials[$row['idsSpecialType']] = $row['chrName'];
		}
		return $specials;
	}
