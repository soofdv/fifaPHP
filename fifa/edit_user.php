<?php

require 'header.php';

$id = $_GET['id'];

$sql = "SELECT * FROM users WHERE id = :id";
$prepare = $pdo->prepare($sql);
$prepare->execute([
    ':id' => $id
]);
$userinfo = $prepare->fetch(PDO::FETCH_ASSOC);

$username = htmlentities($userinfo ['username']);
$admin = htmlentities($userinfo ['admin']);


if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':id' => $_SESSION["id"]
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);

} else {
    header("location: index.php");
}

if ($user ['admin'] != 1 ){
    header("location: index.php");
}// dubbel check dat er geen admin op de dashboard komt

?>

<div class="container">
    <div class="height-page">
        <form class="addteam-form" action="controller.php?id=<?= $id ?>" method="post">
            <input type="hidden" name="type" value="edit_user">

            <h3 class="title-addteam">Team Bewerken</h3>
            <h2 style="color: white;" class="title-addteam"><?= $username ?></h2>

            <label class="form-edit_team" for="admin">Administrator (zo ja vul 1 in)</label>
            <input class="addteam-input" type="text" name="admin" id="admin" value="<?= $admin ?>" >

            <button class="addteam-submit"type="submit">Update</button>

        </form>
    </div>
</div>

<?php require 'footer.php'; ?>