<?php session_start();

//hier komt de connectie naar de database en een json query

$dbHost = "localhost";
$dbName = "fifa";
$dbUser = "root";
$dbPass = "";
//inlog gegevens voor de connectie met de localhost en database
$pdo = new PDO(
    "mysql:host=$dbHost;dbname=$dbName",
    $dbUser,
    $dbPass
);//de connectie word hier gemaakt met de database.

$sql = "SELECT * FROM teams";
$query = $pdo->query($sql);
$teams = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM users";
$query = $pdo->query($sql);
$users = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM matches";
$query = $pdo->query($sql);
$matches = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM referees";
$query = $pdo->query($sql);
$referees = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM fields";
$query = $pdo->query($sql);
$fields = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM match_requirements";
$query = $pdo->query($sql);
$match_requirements = $query->fetchAll(PDO::FETCH_ASSOC);

try{
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "<h1>OEi :(</h1> de connectie met de database is niet gelukt check je config.php";
    die($e->getMessage());
}//error, als er iets verkeerd gaat komt er dit in beeld en geeft het aan dat er wss iets mis is met de connectie naar de localhost.

?>
