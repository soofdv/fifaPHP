<?php require 'header.php';

$id = $_GET['id'];

$sql = "SELECT * FROM matches WHERE id = :id";
$prepare = $pdo->prepare($sql);
$prepare->execute([
    ':id' => $id
]);
$match = $prepare->fetch(PDO::FETCH_ASSOC);

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
//    echo "welkom: {$user['username']}";

}


$team1 = htmlentities($match['team1']);
$team2 = htmlentities($match['team2']);
$team1_score = htmlentities($match['team1_score']);
$team2_score = htmlentities($match['team2_score']);
?>

<div class="container">
    <div class="height-page">
        <form action="controller.php?id=<?=$match ['id']?>" method="post">
            <input type="hidden" name="type" value="scores">
            <div style="display: flex; flex-direction: column;  width: 200px ">
                <label for="team1_score"><?=$team1?></label>
                <input type="text" name="team1_score" id="team1_score">
            </div>
            <div style="display: flex; flex-direction: column; width: 200px">
                <label for="team2_score"><?=$team2?></label>
                <input type="text" name="team2_score" id="team2_score">
            </div>

            <button style="color: white" class="addteam-button" type="submit"> update </button>

        </form>
    </div>
</div>
<?php require 'footer.php'; ?>