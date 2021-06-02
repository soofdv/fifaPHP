<?php
require 'header.php';
$sql = "SELECT * FROM teams";
$query = $pdo->query($sql);
$teams = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM matches";
$query = $pdo->query($sql);
$matches = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM match_requirements";
$query = $pdo->query($sql);
$match_requirements = $query->fetchAll(PDO::FETCH_ASSOC);

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
}

foreach ($match_requirements as $match_requirement) {
    $time = $match_requirement['start_time'];
}
if (!isset($_SESSION['id'])) {
    die("I'm sorry, this page is locked, admins only <a href='login.php'>Login</a> first.");
}
?>
<div class="container">
    <div class="matches-page">
        <?php
            if ($user ['admin'] == 1 )
        echo "<a href='set_time.php'><h5>Maak een wedstrijdschema</h5></a>";
        ?>
        <?php
            echo "Begin tijd is $time";
            foreach ($matches as $match){
                echo "<table style='width: 100%;' class='matchtable'>

                                <tr >
                                    <td>Poule {$match['poule']}</td>
                                    <td>{$match['team1']}</td>
                                    <td>{$match['team1_score']}</td>
                                    <td><h3> <a href='matchdetail.php?id={$match ['id']}'>VS </a></td>
                                    <td>{$match['team2']}</p></td>
                                    <td>{$match['team2_score']}</td>
                                    <td>Duur: {$match['length_match']} min</td>
                                    <td>Rust: {$match['length_rest']} min</td>
                                    <td>Pauze: {$match['length_break']} min</td>
                                    <td>{$match['field_id']}</td>
                                    <td>{$match['referee']}</td>
                                </tr>

                            </table>";
            }
        ?>
    </div>
</div>




<?php require 'footer.php';
?>


