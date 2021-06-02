<?php require 'header.php';
/*
 * dit is het scherm dat je ziet na je bent ingelogd.
 * ook moet je hier verschillende dingen zien zoals wedstrijden die spelen.
 * welke teams er zijn.
 * een download knop.
*/

$sql = "SELECT * FROM teams";
$query = $pdo->query($sql);
$teams = $query->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
            ':id' => $_SESSION["id"]
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);
}
else{
    header("location: index.php");
    exit;
}//als je niet bent in gelogd kan je hier niet komen
if ($user ['admin'] == 1 ){
    header("location: admin.php");

}//dubbel check dat er geen admin op de dashboard komt
?>
    <div class="container">
        <div id="dashboard">
            <?php echo "welkom: {$user['username']}";
            ?>
            <table style='width:100%; text-align: center'><tr><td>team</td> <td>score</td></tr></table>
            <?php
            foreach ($teams as $team){
                $teamname = htmlentities($team['teamname']);
                $teamscore = htmlentities($team['teamscore']);

                echo "<table class='teamnames'>
                        <tr>
                        <td><a  href='detail.php?id={$team ['id']}'>$teamname </a></td>
                        <td><a  href='detail.php?id={$team ['id']}'>$teamscore </a></td>
                        </tr>
                    </table>";
                }
            ?>
            <div class="addteam-button">
                <a href="addteam.php">Team Toevoegen!</a>
            </div>
            <div style="display: flex; justify-content: space-between">
                <a style="color: limegreen" href="matches.php"><h3>Wedstrijden</h3></a>
                <a  href="games/FifaApi.exe"> <button class="download-button">DOWNLOAD NU</button></a>
            </div>
        </div>
    </div>

<?php require 'footer.php'; ?>