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
				$activePage = 'rockPaperScissors';
				include('nav.php');
			?>
		</header>
		<main>
			<section>
				<h1>Rock Paper Scissors</h1>
				<?php
					echo "<h2>SCORE: " . ($_SESSION['rockPaperScissors']->getScore()) . "</h2>";
				?>
				<form method="post">
					<button type="submit" name="move" value="rock">ROCK</button>
					<button type="submit" name="move" value="paper">PAPER</button>
					<button type="submit" name="move" value="scissor">SCISSOR</button>
				</form>
				
				<?php echo(view_errors($errors)); ?> 
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

