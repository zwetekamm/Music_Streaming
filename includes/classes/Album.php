<?php
	class Album {

		private $con;
		private $id;
		private $title;
		private $artistId;
		private $genre;
		private $artworkPath;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;

			/* Query for this DB table and store results as an array */
			/* */
			$query = mysqli_query($this->con, "SELECT * FROM albums WHERE id='$this->id'");
			$album = mysqli_fetch_array($query);

			$this->title = $album['title'];
			$this->artistId = $album['artist'];
			$this->genre = $album['genre'];
			$this->artworkPath = $album['artworkPath'];
		}

		public function getTitle() {
			return $this->title;
		}

		/* Returns a new Artist object with artist id */
		public function getArtist() {
			return new Artist($this->con, $this->artistId);
		}

		public function getGenre() {
			return $this->genre;
		}

		public function getArtworkPath() {
			return $this->artworkPath;
		}

		/* returns a Query for number of songs from a specific album id */
		public function getNumberOfSongs() {
			$query = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id'");
			return mysqli_num_rows($query);
		}

		/* Query for songs IDs for an album, ordered by ascending. */
		/* Then create an array for all song IDs from query and return array. */
		public function getSongIds() {
			$query = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id' ORDER BY albumOrder ASC");

			$songArray = array();

			/* Push ID's to songArray */
			while($row = mysqli_fetch_array($query)) {
				array_push($songArray, $row['id']);
			}

			return $songArray;
		}
	}
?>