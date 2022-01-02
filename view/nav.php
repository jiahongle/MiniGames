<nav>
    <a <?php echo ($activePage == 'allStats') ? 'class="active"' : ''; ?> href="index.php?operation=allStats">ALL STATS</a> 
    <a <?php echo ($activePage == 'guessGame') ? 'class="active"' : ''; ?> href="index.php?operation=guessGame">GUESS GAME</a> 
    <a <?php echo ($activePage == 'rockPaperScissors') ? 'class="active"' : ''; ?> href="index.php?operation=rockPaperScissors">ROCK PAPER SCISSORS</a> 
    <a <?php echo ($activePage == 'frogs') ? 'class="active"' : ''; ?> href="index.php?operation=frogs">FROGS</a> 
    <a <?php echo ($activePage == 'profile') ? 'class="active"' : ''; ?> href="index.php?operation=profile">PROFILE</a> 
    <a <?php echo ($activePage == 'logout') ? 'class="active"' : ''; ?> href="index.php?operation=logout">LOGOUT</a> 
</nav>