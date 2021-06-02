<?php require 'header.php';
if (!isset($_SESSION['id'])) {
    die("I'm sorry, this page is locked, admins only <a href='login.php'>Login</a> first.");
}
if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){//checkt of je ingelogd bent
    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);
    $prepare->execute([
        ':id' => $_SESSION["id"]
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);
}

$sql = "SELECT * FROM referees";
$query = $pdo->query($sql); //verzoek naar de database, voer sql van hierboven uit
$referees = $query->fetchAll(PDO::FETCH_ASSOC);//multie demensionale array //alles binnenhalen
?>
<div class="container">
    <div class="height-page">
        <div>
            <?php
            foreach ($referees as $referee){
                $referee_name = htmlentities($referee['referee_name']);

                echo
                "<li style='hover:margin-left: 5px;'><a  href='refereedetail.php?id={$referee ['id']}'>$referee_name </a></li>";
            }
            ?>
        </div>

        <a href="addreferee.php"><h5>Scheids toevoegen +</h5></a>

        <?php
        if ($user ['admin'] == 1) {
            echo "<form action='controller.php' method='post'>";
            echo "<input type='hidden' name='type' value='reset_referees'>";
            echo "<button class='delete-button' type='submit'>Alles verwijderen</button>
            </form>";
        }
        ?>
    </div>
</div>

<?php require 'footer.php'; ?>