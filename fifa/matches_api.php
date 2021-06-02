<?php require 'config.php';
/*Hier worden alle teams op gepakt uit de matches
*/
if (isset( $_GET['team1'] ) && !empty( $_GET['team1'])){//als team1 is ingevuld dan pakt ie alle informatie op en stopt hij het in een json
    $sql = "SELECT * FROM matches WHERE team1 = :team1";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':team1' => $_GET['team1']
    ]);
    $match = $prepare->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($match);
}
else {//hier vangt ie het op dat ie altijd de informatie oppakt.
    $sql = "SELECT * FROM matches";
    $query = $pdo->query($sql);
    $matches = $query->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($matches);
}
?>

