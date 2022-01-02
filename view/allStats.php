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
				$activePage = 'allStats';
				include('nav.php');
			?>
		</header>
		<main> 
			<section>
				<h1>Stats By Game</h1>
				<?php
					include("stats.php");
				?>
			</section>
			<section class='stats'>
				<h1>YOUR RANKINGS</h1>
					<table class="allStats">
						<tr>
							<tr>
								<th></th>
								<th>RANKING</th>
								<th>SCORE</th>
							</tr>
							<?php if (isset($_SESSION['frogHighscore'])) {
								// Update summary of user's position in highscore leaderboard
								?>
								<th>Frogs</th>
								<td><?php echo $_SESSION['frogHighscore'][0]; ?></td>
								<td><?php echo $_SESSION['frogHighscore'][1]; ?></td>
							<?php
								unset($_SESSION['frogHighscore']);
							}
							else {
								// Display blank scores if user hasn't played any games
								?>
								<th>Frogs</th>
								<td>N/A</td>
								<td>N/A</td>
								<?php
							}
							?>
						</tr>
						<tr>
							<?php if (isset($_SESSION['guessHighscore'])) {
								// Update summary of user's position in highscore leaderboard
								?>
								<th>GG</th>
								<td><?php echo $_SESSION['guessHighscore'][0]; ?></td>
								<td><?php echo $_SESSION['guessHighscore'][1]; ?></td>
							<?php
								unset($_SESSION['guessHighscore']);
							}
							else {
								// Display blank scores if user hasn't played any games
								?>
								<th>GG</th>
								<td>N/A</td>
								<td>N/A</td>
								<?php
							}
							?>
						</tr>
						<tr>
							<?php if (isset($_SESSION['rpsHighscore'])) {
								// Update summary of user's position in highscore leaderboard
								?>
								<th>RPS</th>
								<td><?php echo $_SESSION['rpsHighscore'][0]; ?></td>
								<td><?php echo $_SESSION['rpsHighscore'][1]; ?></td>
							<?php
								unset($_SESSION['rpsHighscore']);
							}
							else {
								// Display blank scores if user hasn't played any games
								?>
								<th>RPS</th>
								<td>N/A</td>
								<td>N/A</td>
								<?php
							}
							?>
						</tr>
					</table>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

