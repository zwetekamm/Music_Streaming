<?php include("includes/header.php"); 
	if(isset($_GET['id'])) {
		$albumId = $_GET['id'];
	}
	else {
		header("Location: index.php");
	}
	/* Assigns new Album object to $album variable */
	$album = new Album($con, $albumId);
	/* Assigns new Artist object to $artist variable */
	$artist = $album->getArtist();
?>

<div class="entityInfo">
	
	<div class="leftSection">
		<img src="<?php echo $album->getArtworkPath(); ?>">
	</div>

	<!--Displays album title, artist name, and number of songs-->
	<div class="rightSection">

		<h2><?php echo $album->getTitle(); ?></h2>
		<p>By <?php echo $artist->getName(); ?></p>
		<p><?php echo $album->getNumberOfSongs(); ?> songs</p>
	
	</div>

</div>

<div class="tracklistContainer">
	
	<!--List of album songs with info and play button-->
	<!--List is unordered to allow formatting freedom when adding our own numbers-->
	<ul class="tracklist">

		<?php
		$songIdArray = $album->getSongIds();

		$i = 1;
		/* Loop each song ID in the album */
		foreach($songIdArray as $songId) {
			$albumSong = new Song($con, $songId);
			$albumArtist = $albumSong->getArtist();

			/* $album__ object call must be outside of string and appended */
			echo "<li class='tracklistRow'>
					
					<div class='trackCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"". $albumSong->getId() ."\", tempPlaylist, true)' alt='Play'>
						<span class='trackNumber'>$i</span>
					</div>

					<div class='trackInfo'>
						<span class='trackName'>". $albumSong->getTitle() ."</span>
						<span class='artistName'>". $albumArtist->getName() ."</span>
					</div>

					<div class='trackOptions'>
						<img class='optionsButton' src='assets/images/icons/more.png'>
					</div>

					<div class='trackDuration'>
						<span class='duration'>". $albumSong->getDuration() ."</span>
					</div>
				
				</li>";
			$i++;
		}
		?>

		<script>
			var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
			tempPlaylist = JSON.parse(tempSongIds);
		</script>

	</ul>

</div>

<?php include("includes/footer.php"); ?>