<?php
require 'header.php';
if (!isset($_SESSION['id'])) {
    die("I'm sorry, this page is locked, admins only <a href='login.php'>Login</a> first.");
}//als je niet een addmin bent en geen session is gestart dan kan je hier niet komen.

if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':id' => $_SESSION["id"]
    ]);
    $users = $prepare->fetch(PDO::FETCH_ASSOC);
}//word gecheckt of je ingelogd bent.
if ($users ['admin'] != 1 ){
    header("location: index.php");

}
$teamsql = "SELECT * FROM teams";//selecteerd alles van data tabel teams
$query = $pdo->query($teamsql);
$teams = $query->fetchAll(PDO::FETCH_ASSOC);//hier word het dan daadwerkelijk opgehaald

?>

<section>
    <div class="container">
        <div class="height-page">
        <div>
            <table style='width:100%; text-align: center'><tr><td>team</td> <td>score</td></tr></table>
            <?php

                foreach ($teams as $team){                      //een foreach loop om alles van de teams tabel te pakken en 1 voor 1 te kijken
                $teamname = htmlentities($team['teamname']);   //hier word per rij in de tabel de 'teamnames' pakken
                $teamscore = htmlentities($team['teamscore']);//hier word per rij in de tabel de 'teamscores' pakken


                echo "<table style='width:100%; text-align: center'> 

                            <tr>
                                <td style='width: 300px;'><a class='teamnames' href='detail.php?id={$team ['id']}'>$teamname</a></td>
                                <td><a  href='detail.php?id={$team ['id']}'>$teamscore</a></td>
                            </tr>
                       </table>";
            }//een link met daarin de id naar welke team die moet en een variable om de juiste teamname en score te laten zien.
            ?>
        </div>
            <a style="color: limegreen" href="matches.php"><h3>Wedstrijden</h3></a>
            <a style="color: limegreen" href="referees.php"><h3>Scheidsrechters</h3></a>
            <a style="color: limegreen" href="fields.php"><h3>Velden</h3></a>
            <a style="color: limegreen" href="users.php">Gebruikers</a>
        </div>
    </div>
</section>


<?php require 'footer.php'; ?>