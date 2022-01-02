<?php
	// So I don't have to deal with uninitialized $_REQUEST['guess']
	$_REQUEST['guess']=!empty($_REQUEST['guess']) ? $_REQUEST['guess'] : '';
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
				$activePage = 'guessGame';
				include('nav.php');
			?>
		</header>
		<main>
			<section>
				<h1>Guess Game</h1>
				<?php echo(view_errors($errors)); ?>
				<?php 
					foreach($_SESSION['GuessGame']->history as $key=>$value){
						echo("<br/> $value");
					}
				?>
				<form method="post">
					<input type="submit" name="submit" value="start again" />
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

