var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var currentIndex = 0;
var mouseDown = false;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

// hides option menu on click event
$(document).click(function(click) {
	var target = $(click.target);
	if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
		hideOptionsMenu();
	}
});

// hides option menu on scroll
$(window).scroll(function() {
	hideOptionsMenu();
});

$(document).on("change", "select.playlist", function() {
	// this is option set
	var select = $(this);
	var playlistId = $(this).val();
	var songId = $(this).prev(".songId").val();

	// ajax post with file, data, and condition
	$.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId })
	.done(function(error) {
		if (error != "") {
			alert(error);
			return;
		}
		// defaults to 'add to playlist'
		hideOptionsMenu();
		select.val("");
	});
});

// Pass in email class as argument to get the email value
function updateEmail(emailClass) {
	var emailValue = $("." + emailClass).val();

	$.post("includes/handlers/ajax/updateEmail.php", { email: emailValue, username: userLoggedIn })
	.done(function(response) {
		// using the email class, sets the text to response
		$("." + emailClass).nextAll(".message").text(response);
	});
}

function updatePassword(oldPasswordClass, newPassword1Class, newPassword2Class) {
	var oldPassword = $("." + oldPasswordClass).val();
	var newPassword1 = $("." + newPassword1Class).val();
	var newPassword2 = $("." + newPassword2Class).val();

	$.post("includes/handlers/ajax/updatePassword.php", 
		{ oldPassword: oldPassword, newPassword1: newPassword1, newPassword2: newPassword2, username: userLoggedIn })
	.done(function(response) {
		// using the old password, sets the text to response
		$("." + oldPasswordClass).nextAll(".message").text(response);
	});
}

function logout() {
	$.post("includes/handlers/ajax/logout.php", function() {
		location.reload();
	});
}

// Encodes the url to be loaded in the Main Content div
function openPage(url) {
	if (timer != null) {
		clearTimeout(timer);
	}
	
	// '?' needed before first variable in url
	if (url.indexOf("?") == -1) {
		url = url + "?";
	}

	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$("#mainContent").load(encodedUrl);
	$("body").scrollTop(0);
	history.pushState(null, null, url);	// puts url into history
}

function removeFromPlaylist(button, playlistId) {
	var songId = $(button).prevAll(".songId").val();

	$.post("includes/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId })
	.done(function(error) {
		if (error != "") {
			alert(error);
			return;
		}
		// open playlist page by id
		openPage("playlist.php?id=" + playlistId);
	});
}

function createPlaylist() {
	var input = prompt("Enter the name of your playlist");

	if (input != null) {
		$.post("includes/handlers/ajax/createPlaylist.php", { name: input, username: userLoggedIn })
		.done(function(error) {
			if (error != "") {
				alert(error);
				return;
			}

			openPage("yourMusic.php");
		})
	}
}

function deletePlaylist(playlistId) {
	var prompt = confirm("Are you sure you want to delete this playlist?");

	if (prompt) {
		$.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: plalistId })
		.done(function(error) {
			if (error != "") {
				alert(error);
				return;
			}

			openPage("yourMusic.php");
		})
	}
}

function hideOptionsMenu() {
	var menu = $(".optionsMenu");
	if (menu.css("display") != "none") {
		menu.css("display", "none");
	}
}

function showOptionsMenu(button) {
	// gets all values of songId class
	var songId = $(button).prevAll(".songId").val();
	var menu = $(".optionsMenu");
	var menuWidth = menu.width();
	menu.find(".songId").val(songId);

	var scrollTop = $(window).scrollTop(); // distance from top of window to top of doc
	var elementOffset = $(button).offset().top;	// button distance from top of doc
	var top = elementOffset - scrollTop;
	var left = $(button).position().left;

	menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" });
}

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

function playFirstSong() {
	setTrack(tempPlaylist[0], tempPlaylist, true);
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