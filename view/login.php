<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7
$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';

// set password variable in $_REQUEST to empty string to clear it
$_REQUEST['password']='';

// Prevent caching on the login page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');
if (isset($_SESSION['token'])) {
	$token = $_SESSION['token'];
}
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
			<nav>
			</nav>
		</header>
		<main>
			<section>
				<h1>Login</h1>
				<form action="index.php" method="post">
					<table>
						<!-- Trick below to re-fill the user form field -->
						<tr><th><label for="user">User</label></th><td><input type="text" name="user" value="<?php echo($_REQUEST['user']); ?>" /></td></tr>
						<tr><th><label for="password">Password</label></th><td> <input type="password" name="password" /></td></tr>
						<tr><th>&nbsp;</th><td><input type="submit" name="submit" value="login" /></td></tr>
						<tr><th>&nbsp;</th><td><input type="hidden" name="curr_token" value="<?=$token ?>"></td></tr>
						<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
					</table>
				</form>
				<a href="index.php?operation=registration">Register</a>
			</section>
			<section>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

