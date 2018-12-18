<?php
	include("includes/config.php");
	include("includes/classes/Account.php");
	include("includes/classes/Constants.php");
	
	$account = new Account($con);

	include("includes/handlers/register_handler.php");
	include("includes/handlers/login_handler.php");

	function getInputValue($n) {
		if(isset($_POST['$n'])) {
			echo $_POST['$n'];
		}
	}
?>

<html>
<head>
	<title>Music Streaming Clone</title>

	<link rel="stylesheet" type="text/css" href="assets/css/register.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>
</head>
<body>
	<?php /* determine which script to output depending on which button is pressed */
		/* returns user to register form if invalid when register button pressed */
		if(isset($_POST['registerButton'])) {
			echo '<script>
					$(document).ready(function() {
						$("#loginForm").hide();
						$("#registerForm").show();
					});
				</script>';
		}
		/* returns user to login form if invalid when login button pressed */
		else {
			echo '<script>
					$(document).ready(function() {
						$("#loginForm").show();
						$("#registerForm").hide();
					});
				</script>';
		}
	?>
	
	<div id="background">

		<div id="loginContainer">
			
			<div id="inputContainer">
				<form id="loginForm" action="register.php" method="POST">
				<h2>Login to your account</h2>
				<p>
					<?php echo $account->getError(Constants::$loginFailed); ?>
					<label for="loginUsername">Username</label>
					<input id="loginUsername" name="loginUsername" type="text" value="<?php getInputValue('loginUsername') ?>" required>
				</p>
				<p>
					<label for="loginPassword">Password</label>
					<input id="loginPassword" name="loginPassword" type="password" required>
				</p>

				<button type="submit" name="loginButton">LOG IN</button>

				<div class="hasAccountText">
					<span id="hideLogin">Don't have an account yet? Sign up here.</span>
				</div>
				</form>

				<form id="registerForm" action="register.php" method="POST">
				<h2>Create your free account</h2>
				<p>
					<?php echo $account->getError(Constants::$usernameLength); ?>
					<?php echo $account->getError(Constants::$usernameExists); ?>
					<label for="username">Username</label>
					<input id="username" name="username" type="text" placeholder="e.g. Beatle123" value="<?php getInputValue('username') ?>" required>
				</p>

				<p>
					<?php echo $account->getError(Constants::$firstNameLength); ?>
					<label for="firstName">First Name</label>
					<input id="firstName" name="firstName" type="text" value="<?php getInputValue('firstName') ?>" required>
				</p>

				<p>
					<?php echo $account->getError(Constants::$lastNameLength); ?>
					<label for="lastName">Last Name</label>
					<input id="lastName" name="lastName" type="text" value="<?php getInputValue('lastName') ?>" required>
				</p>

				<p>
					<?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
					<?php echo $account->getError(Constants::$emailInvalid); ?>
					<?php echo $account->getError(Constants::$emailExists); ?>
					<label for="email">Email</label>
					<input id="email" name="email" type="email" placeholder="e.g. Beatle123@email.com" value="<?php getInputValue('email') ?>" required>
				</p>

				<p>
					<label for="confirmEmail">Confirm Email</label>
					<input id="confirmEmail" name="confirmEmail" type="email" value="<?php getInputValue('confirmEmail') ?>" required>
				</p>

				<p>
					<?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
					<?php echo $account->getError(Constants::$passwordNotAlphaNumeric); ?>
					<?php echo $account->getError(Constants::$passwordLength); ?>
					<label for="password">Password</label>
					<input id="password" name="password" type="password" required>
				</p>

				<p>
					<label for="confirmPassword">Confirm Password</label>
					<input id="confirmPassword" name="confirmPassword" type="password" required>
				</p>

				<button type="submit" name="registerButton">SIGN UP</button>

				<div class="hasAccountText">
					<span id="hideRegister">Already have an account? Log in here.</span>
				</div>
				</form>
			
			</div>
		
			<div id="loginText">
				<h1>Get great music, right now</h1>
				<h2>Listen to your favorite music for free</h2>
				<ul>
					<li>Create your own playlists</li>
					<li>Discover new music you'll love</li>
					<li>Follow artists to keep up to date</li>
				</ul>
			</div>
		</div>

	</div>

</body>
</html>