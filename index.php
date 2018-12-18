<?php include("includes/header.php"); ?>

<h1 class="pageHeadingBig">You Might Also Like</h1>

<div class="gridViewContainer">
	<?php
		/* Query search for all albums, random order, show 5 */
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 5");

		/* Converts $albumQuery results to an array and loop each row result */
		while($row = mysqli_fetch_array($albumQuery)) {

			/* Print album artwork using strings and concatenation */
			echo "<div class='gridViewItem'>

					<a href='album.php?id=". $row['id'] ."'>

						<img src='". $row['artworkPath'] ."'>

						<div class='gridViewInfo'>". $row['title'] ."</div>

					</a>

				</div>";
		}
	?>
</div>

<?php include("includes/footer.php"); ?>