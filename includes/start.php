<?php	
	// RideDB page startup module [includes/start.php]
	
	// bring in some other files
	require_once("lumos/lumos.php");		// user management and MySQL connection information
	require_once('includes/config.php');		// general configuration
	require_once('includes/functions.php');		// common functions
	require_once('includes/Mobile_Detect.php');	// Library to detect whether user is on a mobile device.
	
	// set up some global variables	
	$currentpage = basename($_SERVER['SCRIPT_NAME']);
	$detect = new Mobile_Detect();
	$is_mobile = $detect->isMobile();
	if($detect->isTablet()) { $is_mobile = NULL; }	// If a Tablet, default to Desktop Site instead, as not likely to be using a Tablet on a Park
	
	// check to see whether the user is logged in, and kick them out if not
	// this can be overridden with define('NOLOGIN', 1)
	
	if (!defined('NOLOGIN')) {
		if (!$user) {
			// the user is not logged in; send them to login.php
			header('Location: login.php');
			die('You need to log in to see this page.');
		}
	}
	
	// set default values for variables needed by the templates
	$title = 'Ride DB';
	$refresh = null;
	$showlogout = true;
