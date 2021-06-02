<?php require 'header.php';

$id = $_GET['id'];

$sql = "SELECT username FROM users WHERE id = :id";
$prepare = $pdo->prepare($sql);
$prepare->execute([
    ':id' => $id
]);
$usernames = $prepare->fetch(PDO::FETCH_ASSOC);

$username = $usernames['username'];

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

}
?>
    <div class="container">
        <div class="height-page">

            <h3>&nbsp;<?=$username?></h3>

            <?php if ($user ['admin'] == 1 ){//alleen als je admin bent kan je hier komen.
                echo "<div id='edit-link'> <a  href='edit_user.php?id=$id'>bewerken</a></div>";

                echo "<form action='controller.php?id=$id' method='post'>";
                echo "<input type='hidden' name='type'  value='delete_user'>";
                echo "<button class='delete-button' type='submit' value='delete_field'> Verwijderen </button>
            </form>";
            }?>
        </div>
    </div>
<?php require 'footer.php'; ?>