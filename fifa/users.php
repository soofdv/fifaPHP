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
$sql = "SELECT * FROM users";
$query = $pdo->query($sql); //verzoek naar de database, voer sql van hierboven uit
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="container">
        <div class="height-page">
            <div>
                <?php
                foreach ($users as $user){
                    $username = htmlentities($user['username']);

                    echo
                    "<li style='hover:margin-left: 5px;'><a class='fieldnames' href='userdetail.php?id={$user ['id']}'>$username </a></li>";
                }
                ?>
            </div>
        </div>
    </div>

<?php require 'footer.php'; ?><?php
