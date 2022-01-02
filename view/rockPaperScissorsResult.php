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
				<?php echo(view_errors($errors)); ?>
				<?php
					echo "<h2>SCORE: " . ($_SESSION['rockPaperScissors']->getScore()) . "</h2>";
					echo "<h3>" . $_SESSION['rockPaperScissors']->getState() . "</h3>";
					echo "<h4>Your Move: " . $_SESSION['rockPaperScissors']->getYourMove() . "</h4>";
					echo "<h4>AI Move: " . $_SESSION['rockPaperScissors']->getAiMove() . "</h4><br><br>";
				?>
				<form method="post">
					<button type="submit" name="submit" value="continue">
						Continue	
					</button>
					<button type="submit" name="submit" value="restart">
						Restart Game
					</button>
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

