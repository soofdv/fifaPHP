<?php  require 'header.php';

$id = $_GET['id'];

$sql = "SELECT * FROM referees WHERE id = :id";
$prepare = $pdo->prepare($sql);
$prepare->execute([
    ':id' => $id
]);
$referees = $prepare->fetch(PDO::FETCH_ASSOC);

if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){//checkt of je ingelogd bent
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
$referee_name = htmlentities($referees['referee_name']);
?>
<div class="container">
    <div class="height-page">

        <h3>&nbsp;<?=$referee_name?></h3>

        <?php if ($user ['admin'] == 1 ){
            echo "<form action='controller.php?id=$id' method='post'>";
            echo "<input type='hidden' name='type'  value='delete_referee'>";
            echo "<button class='delete-button' type='submit' value='delete_field'> Verwijderen </button>
    </form>";
        }?>

    </div>
</div>
<?php require 'footer.php'; ?>

