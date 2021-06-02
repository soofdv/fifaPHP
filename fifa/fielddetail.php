<?php require 'header.php';

$id = $_GET['id'];

$sql = "SELECT * FROM fields WHERE id = :id";
$prepare = $pdo->prepare($sql);
$prepare->execute([
    ':id' => $id
]);
$field = $prepare->fetch(PDO::FETCH_ASSOC);


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

if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    $sql = "SELECT * FROM users WHERE id = :id";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':id' => $_SESSION['id']
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);
}
$fieldname = htmlentities($field['fieldname']);

if ($user ['admin'] != 1 ){
    header("location: index.php");

}
?>


<div class="container">
    <div class="height-page">

        <h3>&nbsp;<?=$fieldname?></h3>

        <?php if ($user ['admin'] == 1 ){//alleen als je admin bent kan je hier komen.
            echo "<form action='controller.php?id=$id' method='post'>";
            echo "<input type='hidden' name='type'  value='delete_field'>";
            echo "<button class='delete-button' type='submit' value='delete_field'> Verwijderen </button>
            </form>";
        }?>

    </div>
</div>
<?php require 'footer.php'; ?>