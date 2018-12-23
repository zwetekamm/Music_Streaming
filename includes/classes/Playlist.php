<?php
	class Playlist {
		private $con;
		private $id;
		private $name;
		private $owner;

		// Pass in entire table data and access each element with brackets
		public function __construct($con, $data) {
			// if data is not an array
			if (!is_array($data)) {
				$query = mysqli_query($con, "SELECT * FROM playlists WHERE id='$data'");
				$data = mysqli_fetch_array($query);
			}

			$this->con = $con;
			$this->id = $data['id'];
			$this->name = $data['name'];
			$this->owner = $data['owner'];
		}

		public function getId() {
			return $this->id;
		}

		public function getName() {
			return $this->name;
		}

		public function getOwner() {
			return $this->owner;
		}

		public function getNumberOfSongs() {
			$query = mysqli_query($this->con, "SELECT songId FROM playlistSongs WHERE playlistId='$this->id'");
			return mysqli_num_rows($query);
		}

		public function getSongIds() {
			$query = mysqli_query($this->con, "SELECT songId FROM playlistSongs WHERE playlistId='$this->id' ORDER BY playlistOrder ASC");

			$songArray = array();

			/* Push ID's to songArray */
			while($row = mysqli_fetch_array($query)) {
				array_push($songArray, $row['songId']);
			}

			return $songArray;
		}

		public static function getPlaylistsDropdown($con, $username) {
			$dropdown = '<select class="item playlist">
							<option value="">Add to Playlist</option>';

			$query = mysqli_query($con, "SELECT id, name FROM playlists WHERE owner='$username'");
			while ($row = mysqli_fetch_array($query)) {
				$id = $row['id'];
				$name = $row['name'];
				$dropdown = $dropdown ."<option value='$id'>$name</option>";
			}

			return $dropdown . "</select>";
		}
	}
?>