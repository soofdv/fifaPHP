<?php
//session_start();
require 'header.php';

$id = $_GET['id'];

$sql = "SELECT * FROM teams WHERE id = :id";
$prepare = $pdo->prepare($sql);
$prepare->execute([
    ':id' => $id
]);
$team = $prepare->fetch(PDO::FETCH_ASSOC);

$teamname     =     htmlentities($team ['teamname']);
$teamleader   =     htmlentities($team ['teamleader']);
$participants =     htmlentities($team ['participants']);
$teamscore    =     htmlentities($team['teamscore']);

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
if ($user ['admin'] != 1 ){
    header("location: index.php");

}//dubbel check dat er geen admin op de dashboard komt

?>

<div class="container">
  <div class="height-page">
    <form class="addteam-form" action="controller.php?id=<?=$id?>" method="post">
        <input type="hidden" name="type" value="edit">

        <h2 class="title-addteam">Team Bewerken</h2>


        <label class="form-edit_team" for="teamname">Team naam</label>
        <input class="addteam-input" type="text" name="teamname" id="teamname" value="<?=$teamname?>" placeholder="Team Naam">

        <label class="form-edit_team" for="teamleader">Team leider</label>
        <input class="addteam-input" type="text" name="teamleader" id="teamleader" value="<?=$teamleader?>" placeholder="Team Leider">

        <label class="form-edit_team" for="participants">Aantal spelers</label>
        <input class="addteam-input" type="text" name="participants" id="participants" value="<?=$participants?>" placeholder="Aantal Spelers">

        <label class="form-edit_team" for="teampoints">Totale scoren</label>
        <input class="addteam-input" type="text" name="teamscore" id="teamscore" value="<?=$teamscore?>" placeholder="Aantal Spelers">


        <button class="addteam-submit"type="submit"> update </button>

    </form>
  </div>
</div>

<?php require 'footer.php'; ?>