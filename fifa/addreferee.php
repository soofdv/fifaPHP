<?php require 'header.php';

if (!isset($_SESSION['id'])) {
    die("I'm sorry, this page is locked, admins only <a href='login.php'>Login</a> first.");
}// hier word gekeken of er een session is gestart aka of je ingelogd bent als dat niet zo is dan krijg je de zin te zien.

if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':id' => $_SESSION["id"]
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);
}//Als je bent ingelogd en het klopt dan krijg je de pagina te zien met

?>

<div class="container">
    <div class="height-page">
        <form class="set-timeform" action="controller.php" method="post">
            <input type="hidden" name="type" value="addreferee">

            <input  class="addreferee-input" type="text" name="referee_name" id="referee_name" placeholder="Scheidsrechter Naam">

            <button style="color: white;" class="addteam-button" id="addfield-submit" type="submit"> Toevoegen </button>

        </form>
    </div>
</div>
<?php require 'footer.php'; ?>
