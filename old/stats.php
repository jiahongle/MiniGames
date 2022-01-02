<!DOCTYPE html>
<?php 
    $dbconn = db_connect();
    
    function frogStats($dbconn) {
        $query = "SELECT * FROM frogstats ORDER BY time ASC;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array());
        ?>
        <table class='frogStats'> 
            <tr> 
                <th>POS</th>
                <th>TIME</th>
                <th>PLAYER</th>
            </tr> 
        <?php 
            $position = 1;
            while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
                $user = $row['userid'];
                $time = date("i:s", $row['time']);

                // storing this user's current highest position in leaderboard in SESSION variable
                // to be displayed on "YOUR RANKINGS"
                if ($_SESSION['user'] == $row['userid']) {
                    if (!isset($_SESSION['frogHighscore'])) {
                        $_SESSION['frogHighscore'] = array($position, $time);
                    }
                    echo "<tr class='currUser'><td>$position</td><td>$time</td><td>$user</td></tr>";
                    continue;
                }
                echo "<tr><td>$position</td><td>$time</td><td>$user</td></tr>";
                $position++;
            }
        ?>
        </table>
        <?php
    }

    function guessStats($dbconn) {
        $query = "SELECT * FROM guessstats ORDER BY numcorrect DESC;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array());
        ?>
        <table class='guessStats'> 
            <tr> 
                <th>POS</th>
                <th>TOTAL</th>
                <th>PLAYER</th>
            </tr> 
        <?php 
            $position = 1;
            while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
                $user = $row['userid'];
                $total = $row['numcorrect'];

                // storing this user's current highest position in leaderboard in SESSION variable
                // to be displayed on "YOUR RANKINGS"
                if ($_SESSION['user'] == $row['userid']) {
                    if (!isset($_SESSION['guessHighscore'])) {
                        $_SESSION['guessHighscore'] = array($position, $total);
                    }
                    echo "<tr class='currUser'><td>$position</td><td>$total</td><td>$user</td></tr>";
                    continue;
                }
                // $time = $row['time'];
                echo "<tr><td>$position</td><td>$total</td><td>$user</td></tr>";
                $position++;
            }
        ?>
        </table>
        <?php
    }

    function rpsStats($dbconn) {
        $query = "SELECT * FROM rpsstats ORDER BY ratio DESC;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array());
        ?>
        <table class='rpsStats'> 
            <tr> 
                <th>POS</th>
                <th>WINS</th>
                <th>RATIO</th>
                <th>PLAYER</th>
            </tr> 
        <?php 
            $position = 1;
            while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
                $user = $row['userid'];
                $numwins = $row['numwins'];
                $ratio = $row['ratio'];

                // storing this user's current highest position in leaderboard in SESSION variable
                // to be displayed on "YOUR RANKINGS"
                if ($_SESSION['user'] == $row['userid']) {
                    if (!isset($_SESSION['rpsHighscore'])) {
                        $_SESSION['rpsHighscore'] = array($position, $numwins);
                    }
                    echo "<tr class='currUser'><td>$position</td><td>$numwins</td><td>$ratio</td><td>$user</td></tr>";
                    continue;
                }
                echo "<tr><td>$position</td><td>$numwins</td><td>$ratio</td><td>$user</td></tr>";
                $position++;
            }
        ?>
        </table>
        <?php
    }
    
    // display stats based on activePage
    switch ($activePage) {
        case "frogs":
            frogStats($dbconn);
            break;
        case "guessGame":
            guessStats($dbconn);
            break;
        case "rockPaperScissors":
            rpsStats($dbconn);
            break;
        case "allStats":
            ?>
            <table class="statsByGame">
                <tr>
                    <th>Frogs All-Time</th>
                    <th>Guess Game All-Time</th>
                    <th>RPS All-Time</th>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        <?php frogStats($dbconn); ?>
                    </td>
                    <td style="vertical-align: top;">
                        <?php guessStats($dbconn); ?>
                    </td>
                    <td style="vertical-align: top;">
                        <?php rpsStats($dbconn); ?>
                    </td>
                </tr>
            </table>
            <?php 
            break;
    }

?>