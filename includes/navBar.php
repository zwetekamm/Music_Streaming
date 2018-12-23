<!--Navigation Bar Container-->
<div id="navBarContainer">
	<nav class="navBar">
		
		<!--Logo icon-->
		<span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
			<img src="assets/images/icons/logo.png">
		</span>

		<!--Search group-->
		<div class="group">
			
			<div class="navItem">
				<span role='link' tabindex='0' onclick='openPage("search.php")' class="navItemLink">Search
					<img src="assets/images/icons/search.png" class="icon" alt="Search">
				</span>
			</div>

		</div>

		<!--Browse, Your Music, Profile group-->
		<div class="group">
			
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Browse</span>
			</div>

			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span>
			</div>

			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('settings.php')" class="navItemLink"><?php echo $userLoggedIn->getUsername(); ?></span>
			</div>

		</div>

	</nav>
</div>