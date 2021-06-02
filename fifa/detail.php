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

$teamname = htmlentities($team['teamname']);
$teamleader = htmlentities($team['teamleader']);
$participants = htmlentities($team['participants']);
$creator = htmlentities($team['creator']);
?>


<div class="container">
    <div class="height-page">
        <h2>Teamnaam: <?=$teamname?></h2>
        <p>Leider:&nbsp;<?=$teamleader?></p>
        <p>Aantal spelers:&nbsp;<?=$participants?></p>

        <p>Maker: <?=$creator?></p>

        <?php if ($user ['admin'] == 1 ){//alleen als je admin bent kan je teams verwijderen, bewerken etc.
            echo "<div id='edit-link'> <a  href='edit.php?id=$id'>bewerken</a></div>";

            echo "<form action='controller.php?id=$id' method='post'>";
            echo "<input type='hidden' name='type'  value='delete'>";
            echo "<button class='delete-button' type='submit' value='delete-contact'> verwijderen </button>
            </form> ";
        }?>
    </div>

</div>

<?php require 'footer.php'; ?>