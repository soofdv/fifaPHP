<?php require 'header.php';

if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':id' => $_SESSION["id"]
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);
}//hier word gecheckt of je ingelogd bent en een session is gestart.
else{
    header("location: index.php");
}//als je niet inlogd en je wilt hier komen dan word je terug gestuurd naar de index.

//hier kan je een team toevoegen als je ingelogd bent anders niet

?>

<div class="container">
  <div class="height-page">
    <form class="addteam-form" action="controller.php" method="post">
        <input type="hidden" name="type" value="addteam">

        <input class="addteam-input" type="hidden" name="creator" id="creator">

        <input class="addteam-input" type="text" name="teamname" id="teamname" placeholder="Team Naam">

        <input class="addteam-input" type="text" name="teamleader" id="teamleader" placeholder="Team Leider">

        <input class="addteam-input" type="text" name="participants" id="participants" placeholder="Aantal Spelers">

        <button class="addteam-submit" type="submit"> Toevoegen </button>

    </form>
  </div>
</div>











<?php
require 'footer.php';
?>