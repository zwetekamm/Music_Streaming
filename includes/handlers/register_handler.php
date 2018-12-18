<?php

function clearFormUsername($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);	// concatenate
	return $inputText;
}

function clearFormString($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	$inputText = ucfirst(strtolower($inputText));
	return $inputText;
}

function clearFormPassword($inputText) {
	$inputText = strip_tags($inputText);
	return $inputText;
}

// register button is pressed
if(isset($_POST['registerButton'])) {
	$username = clearFormUsername($_POST['username']);
	$firstName = clearFormString($_POST['firstName']);
	$lastName = clearFormString($_POST['lastName']);
	$email = clearFormString($_POST['email']);
	$confirmEmail = clearFormString($_POST['confirmEmail']);
	$password = clearFormPassword($_POST['password']);
	$confirmPassword = clearFormPassword($_POST['confirmPassword']);

	$wasSuccessful = $account->register($username, $firstName, $lastName, $email, $confirmEmail, $password, $confirmPassword);

	if($wasSuccessful) {
		// create login session variable with user's username
		$_SESSION['userLoggedIn'] = $username;
		
		header("Location: index.php");
	}
}
?>