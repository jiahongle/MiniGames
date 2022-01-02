<?php
	header("Cache-Control", "no-store, no-cache, must-revalidate");
	ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/GuessGame.php";
	require_once "model/rockPaperScissors.php";
	require_once "model/frogs.php";

	session_save_path("sess");
	session_start(); 

	if (!isset($_SESSION['token'])) {
		$_SESSION['token'] = mt_rand();
	}
	
	$dbconn = db_connect();

	$errors=array();
	$view="";

	/* controller code */

	/* If state is not set, set page to login and is_logged_in is false */
	if(!isset($_SESSION['state'])){
		$_SESSION['state']='login';
		$_SESSION['is_logged_in'] = 'no';
	}
	/* Handle operations for users who are not logged in (login, registration) 
	Logged in users must log out first in order to access registration page */
	if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] != 'yes') {
		if ((isset($_GET['operation']) && $_GET['operation'] == 'registration')
				|| $_SESSION['state']=='registration') {
			$_SESSION['state']='registration';
		}
		else {
			$_SESSION['state']='login';
		}
	}
	/* user is logged in in this branch, switch state according to get requests */
	else {
		if (isset($_GET['operation'])) {
			switch ($_GET['operation']) {
				case "allStats":
					$_SESSION['state'] = 'allStats';
					break;
				case "guessGame":
					if ($_SESSION['state'] == 'GuessGameWon') {
						break;	
					}
					$_SESSION['state'] = 'GuessGamePlay';
					break;
				case "rockPaperScissors":
					if ($_SESSION['state'] == 'rockPaperScissorsResult') {
						break;	
					}
					$_SESSION['state'] = 'rockPaperScissorsPlay';
					break;
				case "frogs":
					$_SESSION['state'] = 'frogsPlay';
					break;
				case "profile":
					$_SESSION['state'] = 'profile';
					break;
				case "logout":
					session_destroy();
					session_start();
					$_SESSION['token'] = mt_rand();
					$_SESSION['state'] = 'login';
					$_SESSION['is_logged_in'] = 'no';
					break;
			}
		}
	}
	/* local actions, these are state transforms */
	switch($_SESSION['state']){
		case "login":
			// the view we display by default
			$view="login.php";
			
			// check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="login"){
				break;
			}
			$token = $_SESSION['token'];
			$curr_token = $_REQUEST['curr_token'];
			if ($token != $curr_token) {
				break;
			}
			$_SESSION['token'] = mt_rand();

			// validate and set errors
			if(empty($_REQUEST['user']))$errors[]='Username is required.';
			if(empty($_REQUEST['password']))$errors[]='Password is required.';
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}
			// query db with hashed_pw
			$hashed_pw = hash("sha256", $_REQUEST['password']);

			$query = "SELECT * FROM appuser WHERE userid=$1 and password=$2;";
			$result = pg_prepare($dbconn, "", $query);
			$result = pg_execute($dbconn, "", array($_REQUEST['user'], $hashed_pw));

			if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
				$_SESSION['user']=$_REQUEST['user'];
				$_SESSION['state']='allStats';
				unset($_REQUEST['user']);
				unset($_REQUEST['password']);
				$_SESSION['is_logged_in']='yes';
				$view="allStats.php";
			} else {
				$errors[]="Invalid login.";
			}
			break;
		
		case "registration":
			$view="registration.php";

			if(empty($_REQUEST['submit'])){
				break;
			}
			if(isset($_REQUEST['submit']) && $_REQUEST['submit']=="returnToLogin"){
				$view="login.php";
				$_SESSION['state'] = "login";
				break;
			}
			// sanitize inputs
			if(empty($_REQUEST['user'])) {
				$userErr='Username is required.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(strlen($_REQUEST['user']) > 10) {
				$userErr='Username is too long.';
				$errors[] = 'Check required fields.';
				break;
			}
			if (preg_match("/\s/", $_REQUEST['user'])) {
				$userErr='Username cannot contain whitespaces.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(empty($_REQUEST['password'])) {
				$pwErr='Password is required.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(strlen($_REQUEST['password']) > 20) {
				$pwErr='Password is too long.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(strlen($_REQUEST['password']) < 6) {
				$pwErr='Password is too short.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(empty($_REQUEST['psw-repeat'])) {
				$rptPwErr='Please repeat your password.';
				$errors[] = 'Check required fields.';
				break;
			}
			if($_REQUEST['psw-repeat'] != $_REQUEST['password']) {
				$rptPwErr='Passwords do not match.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(empty($_REQUEST['firstname'])) {
				$firstNameErr='First Name is required.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(strlen($_REQUEST['firstname']) > 20) {
				$firstNameErr='First Name is too long.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(empty($_REQUEST['lastname'])) {
				$lastNameErr='Last Name is required.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(strlen($_REQUEST['lastname']) > 20) {
				$lastNameErr='Last Name is too long.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(empty($_REQUEST['birthdate'])) {
				$birthErr='Birth-date is required.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(empty($_REQUEST['gender'])) {
				$genderErr='Gender is required.';
				$errors[] = 'Check required fields.';
				break;
			}
			if(empty($_REQUEST['favGame'])) {
				$favGameErr='Favourite game is required.';
				$errors[] = 'Check required fields.';
				break;
			}

			if(!$dbconn){
				$errors[]="Can't connect to db.";
				break;
			}
			// checking for existing user with same userid
			$uniqueUserQuery = "SELECT * FROM appuser WHERE userid=$1";
			$result = pg_prepare($dbconn, "", $uniqueUserQuery);
			$result = pg_execute($dbconn, "", array($_REQUEST['user']));
			if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
				$userErr="User already exists.";				
				break;
			}

			// hash password and store hashed version in db
			$hashed_pw = hash("sha256", $_REQUEST['password']);

			$registerQuery = "INSERT into appuser (userid, password, firstname, lastname, birthdate, gender, favgame) 
							values($1, $2, $3, $4, $5, $6, $7)
							ON CONFLICT (userid) DO NOTHING";
			$result = pg_prepare($dbconn, "", $registerQuery);

			$result = pg_execute($dbconn, "", array(
								$_REQUEST['user'], $hashed_pw, $_REQUEST['firstname'], 
								$_REQUEST['lastname'], $_REQUEST['birthdate'], $_REQUEST['gender'], $_REQUEST['favGame']));
								
			// Check that user was properly created!
			$userCreatedQuery = "SELECT * FROM appuser WHERE userid=$1";
			$result = pg_prepare($dbconn, "", $uniqueUserQuery);
			$result = pg_execute($dbconn, "", array($_REQUEST['user']));
			if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
				$view='login.php';
				$_SESSION['state'] = 'login';
			}
			else {
				$errors[] = "Registration failed.";
			}
			break;

		// TODO: TO BE IMPLEMENTED
		case "allStats":
			$view="allStats.php";
			// allStats state
			break;
		case "GuessGamePlay":
			// the view we display by default
			$view="GuessGamePlay.php";
			if (!isset($_SESSION['GuessGame'])) {
				$_SESSION['GuessGame'] = new GuessGame();
			}
			if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'start again') {
				$_SESSION['GuessGame'] = new GuessGame();
			}

			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="guess"){
				break;
			}

			// validate and set errors
			if(!is_numeric($_REQUEST["guess"]))$errors[]="Guess must be numeric.";
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["GuessGame"]->makeGuess($_REQUEST['guess']);
			if($_SESSION["GuessGame"]->getState()=="correct"){
				$_SESSION['state']="GuessGameWon";

				$upsertQuery = "INSERT INTO guessstats (userid, numcorrect) VALUES($1, 1)
								ON CONFLICT (userid)
								DO UPDATE 
								SET numcorrect = guessstats.numcorrect + 1";
				
				$result = pg_prepare($dbconn, "", $upsertQuery);
				$result = pg_execute($dbconn, "", array($_SESSION['user']));

				$view="GuessGameWon.php";
			}
			$_REQUEST['guess']="";
			break;

		case "GuessGameWon":
			// the view we display by default
			$view="GuessGameWon.php";
			// check if submit or not
			if(isset($_REQUEST['submit']) && $_REQUEST['submit']=="start again"){
				$_SESSION["GuessGame"]=new GuessGame();
				$_SESSION['state']="GuessGamePlay";
				$view="GuessGamePlay.php";
			}
			break;
		
		case "rockPaperScissorsPlay":
			$view="rockPaperScissorsPlay.php";

			if (!isset($_SESSION['rockPaperScissors'])) {
				$_SESSION['rockPaperScissors'] = new rockPaperScissors();
			}

			// check if submit or not
			if(empty($_REQUEST['move'])||$_REQUEST['move']==""){
				break;
			}

			// set errors
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["rockPaperScissors"]->makeMove($_REQUEST['move']);

			// update stats for RPS according to game scenarios (win, loss, draw)
			$state = $_SESSION['rockPaperScissors']->getState();
			if ($state == "YOU WIN!") {
				$upsertQuery = "INSERT INTO rpsstats (userid, numwins, numlosses, numgames, ratio) 
								VALUES($1, 1, 0, 1, 0) ON CONFLICT (userid)
								DO UPDATE SET numwins = rpsstats.numwins + 1,
								numgames = rpsstats.numgames + 1,
								ratio = (((rpsstats.numwins+1)::decimal / (rpsstats.numgames+1)) * 100)";
			}
			else if ($state == "YOU LOSE!") {
				$upsertQuery = "INSERT INTO rpsstats (userid, numwins, numlosses, numgames, ratio) 
								VALUES($1, 0, 1, 1, 0) ON CONFLICT (userid)
								DO UPDATE SET numlosses = rpsstats.numlosses + 1,
								numgames = rpsstats.numgames + 1,
								ratio = ((rpsstats.numwins::decimal / (rpsstats.numgames+1)) * 100)";
			}
			else {
				$upsertQuery = "INSERT INTO rpsstats (userid, numwins, numlosses, numgames, ratio) 
								VALUES($1, 0, 0, 1, 0) ON CONFLICT (userid)
								DO UPDATE SET numgames = rpsstats.numgames + 1,
								ratio = ((rpsstats.numwins::decimal / (rpsstats.numgames+1)) * 100)";
			}
			$result = pg_prepare($dbconn, "", $upsertQuery);
			$result = pg_execute($dbconn, "", array($_SESSION['user']));	

			$_SESSION["state"]="rockPaperScissorsResult";
			$view="rockPaperScissorsResult.php";
			$_REQUEST['move']="";
			break;

		case "rockPaperScissorsResult":
			$view="rockPaperScissorsResult.php";

			// validate and set errors
			if(!empty($errors))break;

			// Restart game
			if (isset($_REQUEST['submit'])) {
				if ($_REQUEST['submit'] == "restart") {
					$_SESSION['rockPaperScissors'] = new rockPaperScissors();
				}
				else if ($_REQUEST['submit'] == "continue") {
					$_SESSION['state']="rockPaperScissorsPlay";
					$view="rockPaperScissorsPlay.php";
				}
			}
			
			// perform operation, switching state and view if necessary
			$_SESSION['state']="rockPaperScissorsPlay";
			$view="rockPaperScissorsPlay.php";
			break;
		
		case "frogsPlay":
			$view="frogsPlay.php";
			if (!isset($_SESSION['frogs'])) {
				$_SESSION['frogs'] = new frogs();
				unset($_SESSION['frogRecorded']);
			}
			if (isset($_SESSION['frogWon'])) {
				// place user and score in db
				$query = "INSERT INTO frogstats (userid, time) VALUES($1, $2) 
							ON CONFLICT (userid, time) DO NOTHING;";
				$result = pg_prepare($dbconn, "", $query);
				$result = pg_execute($dbconn, "", array($_SESSION['user'], $_SESSION['frogWon']));

				if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
					unset($_SESSION['frogWon']);
				} else {
					$errors[]="Could not save score.";
				}
				// prevent existing result from being recorded multiple times
				$_SESSION['frogRecorded'] = 'true';
				unset($_SESSION['frogWon']);
			}
			if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'frogRestart') {
				$_SESSION['frogs'] = new frogs();
				// allow new game score to be recorded
				unset($_SESSION['frogRecorded']);
				break;
			}
			if (!isset($_REQUEST['clicked']) || $_REQUEST['clicked'] == "") {
				break;
			}
			
			// set errors
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION['frogs']->clickSquare($_REQUEST['clicked']);
			$_SESSION["state"]="frogsPlay";
			$view="frogsPlay.php";
			$_REQUEST['move'] = "";
			break;

		case "profile":
			$view="profile.php";

			if(!$dbconn){
				$errors[]="Can't connect to db.";
				break;
			}

			// Query Profile information for existing user with given userid
			$userQuery = "SELECT * FROM appuser WHERE userid=$1";
			$result = pg_prepare($dbconn, "", $userQuery);
			$result = pg_execute($dbconn, "", array($_SESSION['user']));
			if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
				if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'update') {
					if(empty($_REQUEST['user'])) {
						$userErr='Username is required.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(strlen($_REQUEST['user']) > 10) {
						$userErr='Username is too long.';
						$errors[] = 'Check required fields.';
						break;
					}
					if (preg_match("/\s/", $_REQUEST['user'])) {
						$userErr='Username cannot contain whitespaces.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(!empty($_REQUEST['password']) && strlen($_REQUEST['password']) > 20) {
						$pwErr='Password is too long.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(!empty($_REQUEST['password']) && strlen($_REQUEST['password']) < 6) {
						$pwErr='Password is too short.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(!empty($_REQUEST['password']) && empty($_REQUEST['psw-repeat'])) {
						$rptPwErr='Please repeat your password.';
						$errors[] = 'Check required fields.';
						break;
					}
					if($_REQUEST['psw-repeat'] != $_REQUEST['password']) {
						$rptPwErr='Passwords do not match.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(empty($_REQUEST['firstname'])) {
						$firstNameErr='First Name is required.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(strlen($_REQUEST['firstname']) > 20) {
						$firstNameErr='First Name is too long.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(empty($_REQUEST['lastname'])) {
						$lastNameErr='Last Name is required.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(strlen($_REQUEST['lastname']) > 20) {
						$lastNameErr='Last Name is too long.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(empty($_REQUEST['birthdate'])) {
						$birthErr='Birth-date is required.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(empty($_REQUEST['gender'])) {
						$genderErr='Gender is required.';
						$errors[] = 'Check required fields.';
						break;
					}
					if(empty($_REQUEST['favGame'])) {
						$favGameErr='Favourite game is required.';
						$errors[] = 'Check required fields.';
						break;
					}
					// use existing password if none specified
					if (empty($_REQUEST['password']) && empty($_REQUEST['psw-repeat'])) {
						$hashed_pw = $row['password'];
					}
					else {
						// hash new password
						$hashed_pw = hash("sha256", $_REQUEST['password']);
					}
					
					// If user wants to change username, check for unique
					if ($_REQUEST['user'] != $_SESSION['user']) {
						// checking for existing user with same userid
						$uniqueUserQuery = "SELECT * FROM appuser WHERE userid=$1";
						$result = pg_prepare($dbconn, "", $uniqueUserQuery);
						$result = pg_execute($dbconn, "", array($_REQUEST['user']));
						if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
							$userErr="User already exists.";				
							break;
						}
						// set to new username after completing checks
					}

					$updateQuery = "UPDATE appuser 
							SET (userid, password, firstname, lastname, birthdate, gender, favgame) = 
							($1, $2, $3, $4, $5, $6, $7)
							WHERE userid=$8";
					$result = pg_prepare($dbconn, "", $updateQuery);
					$result = pg_execute($dbconn, "", array(
							$_REQUEST['user'], $hashed_pw, $_REQUEST['firstname'], 
							$_REQUEST['lastname'], $_REQUEST['birthdate'], $_REQUEST['gender'], 
							$_REQUEST['favGame'], $_SESSION['user']));
				
					echo pg_result_error($result);
					if ($result == false) {
						$errors[] = "Nothing to update.";
						break;
					}
					else {
						$_SESSION['user'] = $_REQUEST['user'];
						$view="profile.php";
						break;
					}
				}
				$_REQUEST['user']=$row['userid'];
				$_REQUEST['firstname']=$row['firstname'];
				$_REQUEST['lastname']=$row['lastname'];
				$_REQUEST['birthdate']=$row['birthdate'];
				$_REQUEST['gender']=$row['gender'];
				$_REQUEST['favGame']=$row['favgame'];
			}
			else {
				$errors[] = "Could not find user's profile data.";
			}
			break;
	}
	require_once "view/$view";
?>
