<?php
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games</title>
	</head>
	<body>
		<header>
			<?php
				$activePage = 'frogs';
				include('nav.php');
			?>
		</header>
		<main>
			<section>
				<h1>Frogs</h1>
				<?php 
					$spots = $_SESSION['frogs']->getSpots();
				?>
				<table class='frogSpots'>
					<form method="post">
					<tr>
						<?php 
							// display image in each spot according to array representing frog position
							foreach ($spots as$key => $value) {
								$img = "";
								switch ($value) {
									case 1:
										$img = "gifs/yellowFrog.gif";
										break;
									case 0:
										$img = "gifs/empty.gif";
										break;
									case -1:
										$img = "gifs/greenFrog.gif";
										break;
								}
								echo "<td rowspan='2'>
										<button type='submit' class='frogs' name='clicked' value='$key'>
											<img src='$img' alt='frog' class='frogImg'><br>
											<img src='img/lily-pad.svg' alt='lilypad' class='lilypadImg'/>
										</button>
									</td>";
							}
						?>
					</tr>
					</form>
				</table>
				<?php
					if ($_SESSION['frogs']->checkWin()) {
						echo "<h2>YOU WIN</h2>";
						$elapsed_sec = $_SESSION['frogs']->getTime();

						// format elapsed time using date()
            			$elapsed_time = date("i:s", $elapsed_sec); 
						echo "<h3>YOUR TIME: " . $elapsed_time. "</h3>";
						if (!isset($_SESSION['frogRecorded'])) {
							$_SESSION['frogWon'] = $elapsed_sec;
						}
						// prevent result from the same game from being logged twice
						else {
							unset($_SESSION['frogWon']);
						}
					}
				?>
				<form method="post">
					<button type="submit" name="submit" value="frogRestart">Restart Game</button>
				</form>
			</section>
			<section class='stats'>
				<h1>LEADERBOARDS</h1>
				<?php 
					include("stats.php");
				?> 
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

