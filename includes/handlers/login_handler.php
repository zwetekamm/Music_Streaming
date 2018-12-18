<?php

// login button is pressed
if(isset($_POST['loginButton'])) {
	$username = $_POST['loginUsername'];
	$password = $_POST['loginPassword'];

	$result = $account->login($username, $password);

	if($result) {
		// create login session variable with user's username
		$_SESSION['userLoggedIn'] = $username;

		// redirect user to index page
		header("Location: index.php");
	}
}

?>