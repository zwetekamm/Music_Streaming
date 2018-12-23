<?php
	include("includes/config.php");
	include("includes/classes/User.php");
	include("includes/classes/Artist.php");
	include("includes/classes/Album.php");
	include("includes/classes/Song.php");
	include("includes/classes/Playlist.php");

	if(isset($_SESSION['userLoggedIn'])) {
		// assigns user object (logged in) to new variable
		$userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
		$username = $userLoggedIn->getUsername();
		// set variable to javascript
		echo "<script>userLoggedIn = '$username';</script>";
	} else {
		// redirect user to register page
		header("Location: register.php");
	}
?>

<html>
<head>
	<title>Music Streaming Clone</title>

	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="assets/js/script.js"></script>
</head>
<body>

	<!--Container for Main Content, Navigation Bar, and Now Playing Bar-->
	<div id="mainContainer">
		
		<!--Container for Main Content and Navigation Bar-->
		<div id="topContainer">

			<!--Navigation Bar-->
			<?php include("includes/navBar.php"); ?>

			<div id="mainViewContainer">
				
				<div id="mainContent">