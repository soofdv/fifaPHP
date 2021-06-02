<?php require 'header.php';
if (!isset($_SESSION['id'])) {
    die("I'm sorry, this page is locked, admins only <a href='login.php'>Login</a> first.");
}
if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':id' => $_SESSION["id"]
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);
}
if ($user ['admin'] != 1 ){
    header("location: index.php");

}
$sql = "SELECT * FROM fields";
$query = $pdo->query($sql); //verzoek naar de database, voer sql van hierboven uit
$fields = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
    <div class="height-page">
    <div>
        <?php
        foreach ($fields as $field){
            $fieldname = htmlentities($field['fieldname']);

            echo
            "<li style='hover:margin-left: 5px;'><a class='fieldnames' href='fielddetail.php?id={$field ['id']}'>$fieldname </a></li>";
        }
        ?>
    </div>

    <a href="addfield.php"><h5>Veld toevoegen +</h5></a>

    <form action="controller.php" method="post">
        <input type="hidden" name="type" value='reset_fields'>
        <button class='delete-button' type='submit'>Alles verwijderen</button>
    </form>

    </div>
</div>

<?php require 'footer.php'; ?>