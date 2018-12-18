<?php
	ob_start();
	session_start();
	
	$timezone = date_default_timezone_set("America/Chicago");

	// connection variable
	$con = mysqli_connect("localhost", "root", "", "music_streaming_clone");

	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno();
	}
?>