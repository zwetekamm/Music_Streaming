<?php
	class Artist {

		private $con;
		private $id;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;
		}

		public function getId() {
			return $this->id;
		}

		public function getName() {
			/* Query for this artist id and return result as an array */
			$query = mysqli_query($this->con, "SELECT name FROM artists WHERE id='$this->id'");
			$artist = mysqli_fetch_array($query);
			return $artist['name'];
		}

		/* Query for songs IDs for an artist; shows most played in descending order */
		public function getSongIds() {
			$query = mysqli_query($this->con, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC");
			$songArray = array();
			/* Push ID's to songArray */
			while($row = mysqli_fetch_array($query)) {
				array_push($songArray, $row['id']);
			}
			return $songArray;
		}
	}
?>