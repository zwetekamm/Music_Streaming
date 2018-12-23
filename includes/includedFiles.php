<?php
// If came from Ajax request; else load header/footer content
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	include("includes/config.php");
	// Must be descending order for use
	include("includes/classes/User.php");
	include("includes/classes/Artist.php");
	include("includes/classes/Album.php");
	include("includes/classes/Song.php");
	include("includes/classes/Playlist.php");

	if (isset($_GET['userLoggedIn'])) {
		$userLoggedIn = new User($con, $_GET['userLoggedIn']);
	} else {
		echo "Username variable not passed into page.";
	}

} else {
	include("includes/header.php");
	include("includes/footer.php");

	$url = $_SERVER['REQUEST_URI'];
	echo "<script>openPage('$url')</script>";
	exit();
}

?>