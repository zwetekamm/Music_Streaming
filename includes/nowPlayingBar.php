<?php

	$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

	$resultArray = array();

	while($row = mysqli_fetch_array($songQuery)) {
		/* Push song ID to the resultArray */
		array_push($resultArray, $row['id']);
	}

	/* Convert php resultArray to json */
	$jsonArray = json_encode($resultArray);
?>

<script>
	/* Render page before execution */
	$(document).ready(function() {
		// array of song IDs
		var newPlaylist = <?php echo $jsonArray; ?>;
		audioElement = new Audio();
		setTrack(newPlaylist[0], newPlaylist, false);
		updateVolumeProgressBar(audioElement.audio);

		// Hides highlighting behavior
		$("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
			e.preventDefault();
		});

		// Center Progress Bar
		$(".playbackBar .progressBar").mousedown(function() {
			mouseDown = true;
		});

		$(".playbackBar .progressBar").mousemove(function(e) {
			if (mouseDown) {
				// Set time of song depending on postion of the mouse
				timeFromOffset(e, this);
			}
		});

		$(".playbackBar .progressBar").mouseup(function(e) {
			// Set time of song depending on postion of the mouse
			timeFromOffset(e, this);
		});

		// Volume Bar
		$(".volumeBar .progressBar").mousedown(function() {
			mouseDown = true;
		});

		$(".volumeBar .progressBar").mousemove(function(e) {
			if (mouseDown) {
				var percentage = e.offsetX / $(this).width();
				if (percentage >= 0 && percentage <= 1) {
					audioElement.audio.volume = percentage;	
				}
			}
		});

		$(".volumeBar .progressBar").mouseup(function(e) {
			var percentage = e.offsetX / $(this).width();
			if (percentage >= 0 && percentage <= 1) {
				audioElement.audio.volume = percentage;	
			}
		});

		$(document).mouseup(function() {
			mouseDown = false;
		});
	});

	// Get time from offset of mouse
	function timeFromOffset(mouse, progressBar) {
		// offset along x-axis
		var percentage = mouse.offsetX / $(progressBar).width() * 100;
		var seconds = audioElement.audio.duration * (percentage / 100);
		audioElement.setTime(seconds);
	}

	function prevSong() {
		// replay song from beginning if more than three seconds
		if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
			audioElement.setTime(0);
		} else {
			currentIndex--;
			setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
		}
	}

	function nextSong() {
		// if repeat is selected
		if (repeat) {
			audioElement.setTime(0);
			playSong();
			return;
		}

		// increment the index to play next song in song array
		if (currentIndex == currentPlaylist.length - 1) {
			currentIndex = 0;
		} else {
			currentIndex++;
		}
		// If shuffle is active, track play comes from shuffle playlist; otherwise from current playlist
		var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
		setTrack(trackToPlay, currentPlaylist, true);
	}

	function setRepeat() {
		repeat = !repeat;
		// Conditional if repeat true, use active image; otherwise use inactive image
		var imageName = repeat ? "repeat-active.png" : "repeat.png";
		$(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
	}

	function setMute() {
		audioElement.audio.muted = !audioElement.audio.muted;
		var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
		$(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
	}

	function setShuffle() {
		shuffle = !shuffle;
		var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
		$(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

		if (shuffle) {
			// Randomize and assign current index to shuffled playlist
			shuffleArray(shufflePlaylist);
			currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
		} else {
			// Unshuffle and return to original playlist
			currentIndex = currentlyPlaylist.indexOf(audioElement.currentlyPlaying.id);
		}
	}

	// Shuffle the song list for playlist
	function shuffleArray(a) {
		var i, j, x;
		for (i = a.length; i; i--) {
			j = Math.floor(Math.random() * i);
			// perform the array swap
			x = a[i - 1];
			a[i - 1] = a[j];
			a[j] = x;
		}
	}

	/* Function to display song/artist/album info in bottom-left of Now Playing Bar*/
	function setTrack(trackId, newPlaylist, play) {
		if (newPlaylist != currentPlaylist) {
			currentPlaylist = newPlaylist;
			shufflePlaylist = currentPlaylist.slice();
			shuffleArray(shufflePlaylist);
		}

		if (shuffle) {
			currentIndex = shufflePlaylist.indexOf(trackId);
		} else {
			currentIndex = currentPlaylist.indexOf(trackId);			
		}
		pauseSong();

		// Display song title
		$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {
			var track = JSON.parse(data);
			$(".trackName span").text(track.title);

			// Display artist name (nested because artist ID is retrieved from song data)
			$.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
				var artist = JSON.parse(data);
				$(".artistName span").text(artist.name);
			});

			// Display album image (also nested)
			$.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
				var album = JSON.parse(data);
				$(".albumLink img").attr("src", album.artworkPath);
			});

			audioElement.setTrack(track);
			playSong();
		});

		if (play) {
			audioElement.play();
		}
	}

	function playSong() {
		if(audioElement.audio.currentTime == 0) {
			$.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
		}

		$(".controlButton.play").hide();
		$(".controlButton.pause").show();
		audioElement.play();
	}

	function pauseSong() {
		$(".controlButton.play").show();
		$(".controlButton.pause").hide();
		audioElement.pause();
	}
</script>

<!--Now Playing container; spans width at bottom of page-->
<div id="nowPlayingBarContainer">

	<div id="nowPlayingBar">

		<!--Left-->
		<div id="nowPlayingLeft">
			
			<div class="content">
				<!--Album artwork-->
				<span class="albumLink">
					<img src="" class="albumArtwork">
				</span>

				<!--Track details: title and artist-->
				<div class="trackInfo">

					<span class="trackName">
						<span></span>
					</span>

					<span class="artistName">
						<span></span>
					</span>
				
				</div>

			</div>

		</div>

		<!--Center-->
		<div id="nowPlayingCenter">
			
			<div class="content playerControls">
				
				<div class="buttons">
					<!--Shuffle button-->
					<button class="controlButton shuffle" title="Shuffle" onclick="setShuffle()">
						<img src="assets/images/icons/shuffle.png" alt="Shuffle">
					</button>

					<!--Previous button-->
					<button class="controlButton previous" title="Previous" onclick="prevSong()">
						<img src="assets/images/icons/previous.png" alt="Previous">
					</button>

					<!--Play button-->
					<button class="controlButton play" title="Play" onclick="playSong()">
						<img src="assets/images/icons/play.png" alt="Play">
					</button>

					<!--Pause button; not displayed until Play button pressed-->
					<button class="controlButton pause" title="Pause" style="display: none;" onclick="pauseSong()">
						<img src="assets/images/icons/pause.png" alt="Pause">
					</button>

					<!--Next button-->
					<button class="controlButton next" title="Next" onclick="nextSong()">
						<img src="assets/images/icons/next.png" alt="Next">
					</button>

					<!--Repeat button-->
					<button class="controlButton repeat" title="Repeat" onclick="setRepeat()">
						<img src="assets/images/icons/repeat.png" alt="Repeat">
					</button>
				
				</div>

				<div class="playbackBar">
					<!--Current play time-->
					<span class="progressTime current">0.00</span>

					<!--Progress Bar background and progress visual-->
					<div class="progressBar">
						<div class="progressBarBg">
							<div class="progress"></div>
						</div>
					</div>

					<!--Play time remaining-->
					<span class="progressTime remaining">0.00</span>
				
				</div>

			</div>

		</div>

		<!--Right-->
		<div id="nowPlayingRight">
			
			<div class="volumeBar">
				<!--Volume button-->
				<button class="controlButton" title="Volume" onclick="setMute()">
					<img src="assets/images/icons/volume.png" alt="Volume">
				</button>

				<!--Volume slider-->
				<div class="progressBar">
					<div class="progressBarBg">
						<div class="progress"></div>
					</div>
				</div>

			</div>

		</div>

	</div>

</div>