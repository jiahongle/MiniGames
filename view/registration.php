<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
$_REQUEST['firstname']=!empty($_REQUEST['firstname']) ? $_REQUEST['firstname'] : '';
$_REQUEST['lastname']=!empty($_REQUEST['lastname']) ? $_REQUEST['lastname'] : '';
$_REQUEST['birthdate']=!empty($_REQUEST['birthdate']) ? $_REQUEST['birthdate'] : '';
$_REQUEST['gender']=!empty($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
$_REQUEST['favGame']=!empty($_REQUEST['favGame']) ? $_REQUEST['favGame'] : '';
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
				<h1>Register To Play</h1>
				<form action="index.php" method="post">
					<!-- <legend></legend> -->
					<table>
						<!-- Trick below to re-fill the user form field -->
						<tr>
							<th><label for="user">Username</label></th>
							<td><input <?php if (isset($userErr)) echo "class='err'"; ?> 
									type="text" name="user" value="<?php echo($_REQUEST['user']); ?>" placeholder="Enter Username"/></td>
							<td class='errorMsg'><?php if (isset($userErr)) echo $userErr;?></td>
						</tr>
						<tr>
							<th><label for="password">Password</label></th>
							<td><input <?php if (isset($pwErr)) echo "class='err'"; ?> 
									type="password" name="password" placeholder="Enter Password"/></td>
							<td class='errorMsg'><?php if (isset($pwErr)) echo $pwErr;?></td>
						</tr>
						<tr>
							<th><label for="psw-repeat">Repeat Password</label></th>
							<td><input <?php if (isset($rptPwErr)) echo "class='err'"; ?> 
									type="password" name="psw-repeat" placeholder="Enter Password Again"/></td>
							<td class='errorMsg'><?php if (isset($rptPwErr)) echo $rptPwErr;?></td>
						</tr>
						<tr>
							<th><label for="firstname">First Name</label></th>
							<td><input <?php if (isset($firstNameErr)) echo "class='err'"; ?> 
									type="text" name="firstname" value="<?php echo($_REQUEST['firstname']); ?>" placeholder="Enter First Name"/></td>
							<td class='errorMsg'><?php if (isset($firstNameErr)) echo $firstNameErr;?></td>
						</tr>
						<tr>
							<th><label for="lastname">Last Name</label></th>
							<td><input <?php if (isset($lastNameErr)) echo "class='err'"; ?> 
									type="text" name="lastname" value="<?php echo($_REQUEST['lastname']); ?>" placeholder="Enter Last Name"/></td>
							<td class='errorMsg'><?php if (isset($lastNameErr)) echo $lastNameErr;?></td>
						</tr>
						
						<tr>
							<th><label for="birthdate">Birth-date</label></th>
							<td><input <?php if (isset($birthErr)) echo "class='err'"; ?> 
									type="date" max="<?php echo date('Y-m-d'); ?>" name="birthdate" value="<?php echo($_REQUEST['birthdate']); ?>"/></td>
							<td class='errorMsg'><?php if (isset($birthErr)) echo $birthErr;?></td>
						</tr>
						<tr>
							<th>Gender</th>
							<td>
								<input <?php if (isset($genderErr)) echo "class='err'"; ?> 
										type="radio" id="male" name="gender" value="male" <?php if ($_REQUEST['gender'] == 'male') echo "checked='checked'"; ?>>
								<label for="male">Male</label>

								<input <?php if (isset($genderErr)) echo "class='err'"; ?> 
										type="radio" id="female" name="gender" value="female" <?php if ($_REQUEST['gender'] == 'female') echo "checked='checked'"; ?>>
								<label for="female">Female</label>

								<input <?php if (isset($genderErr)) echo "class='err'"; ?> 
										type="radio" id="other" name="gender" value="other" <?php if ($_REQUEST['gender'] == 'other') echo "checked='checked'"; ?>>
								<label for="other">Other</label>
							</td>
							<td class='errorMsg'><?php if (isset($genderErr)) echo $genderErr;?></td>
						</tr>
						<tr>
							<th><label for="favGame">Choose your favourite game</label></th>
							<td>
								<select name="favGame" id="favGame">
									<option value="GuessGame" <?php if ($_REQUEST['favGame'] == 'GuessGame') echo "selected='selected'"; ?>>
										Guess Game
									</option>
									<option value="RockPaperScissors" <?php if ($_REQUEST['favGame'] == 'RockPaperScissors') echo "selected='selected'"; ?>>
										Rock Paper Scissors
									</option>
									<option value="Frogs" <?php if ($_REQUEST['favGame'] == 'Frogs') echo "selected='selected'"; ?>>
										Frogs
									</option>
									<option value="Other" <?php if ($_REQUEST['favGame'] == 'Other') echo "selected='selected'"; ?>>
										Other
									</option>
								</select>
							</td>
						</tr>
						<tr><th>&nbsp;</th></tr>
						<tr><th>&nbsp;</th></tr>
						<tr><th>&nbsp;</th></tr>
						<tr>
							<th>&nbsp;</th>
							<td>
								<input type="checkbox" id="tAndC" name="terms" required='required'/> 
								<label for="terms">I agree to the Terms of Service and Privacy Policy.</label>
							</td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<td>
								<button type="submit" name="submit" value="register">Register</button>
							</td>
						</tr>
						<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
					</table>
				</form>
				<form action="index.php" method="post">
					<td>
						<button type="submit" name="submit" value="returnToLogin">Return to Login</button>
					</td>
				</form>	
			</section>
			<section>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

