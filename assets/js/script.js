var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var currentIndex = 0;
var mouseDown = false;
var repeat = false;
var shuffle = false;

// Time remaining--right side of Now Playing Bar
function formatTime(seconds) {
	var time = Math.round(seconds);
	var minutes = Math.floor(time / 60);
	var seconds = time - minutes * 60;
	var extraZero = (seconds < 10) ? "0" : "";

	return minutes + ":" + extraZero + seconds;
}

// Time progressed--left side and center of Now Playing Bar
function updateTimeProgressBar(audio) {
	// Left side
	$(".progressTime.current").text(formatTime(audio.currentTime));
	$(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));
	
	// Center--Calculates the percentage of progress and displays using CSS
	var progress = audio.currentTime / audio.duration * 100;
	$(".playbackBar .progress").css("width", progress + "%");
}

// Volume Bar progress
function updateVolumeProgressBar(audio) {
	var volume = audio.volume * 100;
	$(".volumeBar .progress").css("width", volume + "%");
}

function Audio() {
	this.currentlyPlaying;
	this.audio = document.createElement('audio');

	// Next song when current song ends
	this.audio.addEventListener("ended", function() {
		nextSong();
	});

	// Event called to the audio object
	this.audio.addEventListener("canplay", function() {
		var duration = formatTime(this.duration);
		$(".progressTime.remaining").text(duration);
		updateVolumeProgressBar(this);
	});

	this.audio.addEventListener("timeupdate", function() {
		if (this.duration) {
			updateTimeProgressBar(this);
		}
	});

	this.audio.addEventListener("volumechange", function() {
		updateVolumeProgressBar(this);
	});

	// Sets currentlyPlaying  and audio src each time function is called.
	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

	this.play = function() {
		this.audio.play();
	}

	this.pause = function() {
		this.audio.pause();
	}

	this.setTime = function(seconds) {
		this.audio.currentTime = seconds;
	}
}