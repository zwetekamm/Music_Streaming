<?php
	class Account {

		private $con;
		private $errorArray;

		public function __construct($con) {
			$this->con = $con;
			$this->errorArray = array();
		}

		public function login($un, $pw) {
			// encrypt
			$pw = md5($pw);

			$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");
			
			if(mysqli_num_rows($query) == 1){
				return true;
			}
			else {
				array_push($this->errorArray, Constants::$loginFailed);
				return false;
			}
		}
		public function register($un, $fn, $ln, $em, $cem, $pw, $cpw) {
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmail($em, $cem);
			$this->validatePassword($pw, $cpw);

			if(empty($this->errorArray)) {

				// insert into database
				return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
			}
			else {
				return false;
			}
		}

		// check if $error parameter exists in the error array
		public function getError($error) {
			if(!in_array($error, $this->errorArray)) {
				$error = "";
			}
			return "<span class='errorMessage'>$error</span>";
		}

		// values to insert into the database
		private function insertUserDetails($un, $fn, $ln, $em, $pw) {
			$encryptedPw = md5($pw);
			$profilePic = "assests/images/profile_pics/pro.jpg";
			$date = date("Y-m-d");

			// insert values by database structure
			// first value 'id' is empty because it's auto-incremented
			// $result is true if successful
			$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

			return $result;
		}

		private function validateUsername($un) {
			if(strlen($un) < 5 || strlen($un) > 25) {
				array_push($this->errorArray, Constants::$usernameLength);
				return;
			}

			// check if username already exists
			$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
			if(mysqli_num_rows($checkUsernameQuery) != 0){
				array_push($this->errorArray, Constants::$usernameExists);
				return;
			}
		}

		private function validateFirstName($fn) {
			if(strlen($fn) < 1 || strlen($fn) > 25) {
				array_push($this->erroryArray, Constants::$firstNameLength);
				return;
			}
		}

		private function validateLastName($ln) {
			if(strlen($ln) < 1 || strlen($ln) > 25) {
				array_push($this->errorArray, Constants::$lastNameLength);
				return;
			}
		}

		private function validateEmail($em, $cem) {
			if($em != $cem) {
				array_push($this->errorArray, Constants::$emailsDoNotMatch);
				return;
			}

			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray, Constants::$emailInvalid);
				return;
			}

			// check if email already exists
			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
			if(mysqli_num_rows($checkEmailQuery) != 0){
				array_push($this->errorArray, Constants::$emailExists);
				return;
			}
		}

		private function validatePassword($pw, $cpw) {
			if($pw != $cpw) {
				array_push($this->errorArray, Constants::$passwordsDoNotMatch);
				return;
			}

			// if not A-Z, a-z, 0-9
			if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constants::$passwordsNotAlphaNumeric);
				return;
			}

			if(strlen($pw) < 5 || strlen($pw) > 25) {
				array_push($this->errorArray, Constants::$passwordLength);
				return;
			}
		}
	}
?>