<?php
	//RideDB Footer Page [includes/footer.php]
	if (($showlogout) AND ($detect->isMobile())) {
		?>
		
		<a href="logout.php">&raquo; Logout</a>
	<?php
	}
?>

	</div>
	</div><?php if(!$detect->isMobile()) { ?>

	<div id="footer">RideDB System: &copy; 2013 Russell Ede & Thomas Preece.</div>
<?php } ?>
</div>
</body>
</html>
