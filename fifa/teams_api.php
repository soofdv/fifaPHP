<?php require 'config.php';
//hier worden alle namen van de teams op gepakt uit de 'team' tabel en in een json file gezet
if (isset( $_GET['teamname'] ) && !empty( $_GET['teamname'])){
    $sql = "SELECT * FROM teams WHERE teamname = :teamname";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':teamname' => $_GET['teamname']
    ]);
    $team = $prepare->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($team);
}
else {//als er iets fout gaat dan word het zeker opgehaald.
    $sql = "SELECT * FROM teams";
    $query = $pdo->query($sql);
    $teams = $query->fetchAll(PDO::FETCH_ASSOC);
    $teamNames['names'] = array();
    $length = count($teams) - 1;
    for ($i = 0; $i <= $length; $i++) {
        $teamNames['names'][] = $teams[$i]['teamname'];
    }
    header('Content-Type: application/json');
    echo json_encode($teamNames);
}

?>
