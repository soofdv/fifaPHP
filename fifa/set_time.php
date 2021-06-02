<?php require 'header.php';
$sql = "SELECT * FROM teams";
$query = $pdo->query($sql);
$teams = $query->fetchAll(PDO::FETCH_ASSOC);

$teamscount = count($teams);
?>
<div class="container">
    <div class="height-page">
        <form class="set-timeform" action="controller.php" method="post">
            <input type='hidden' name='type' value='create-match'>
            <div class="set-time-box">
                <label for="start_time">Begin tijd van de wedstrijden</label>
                <input class="set-time-input" type="time" name="start_time" id="start_time">
            </div>

            <div class="set-time-box">
                <label for="match_length">Wedstrijdsduur (minuten)</label>
                <input class="set-time-input" type="text" name="match_length" id="match_length">
            </div>

            <div class="set-time-box" >
                <label for="rest">Tussenpauze minuten</label>
                <input class="set-time-input" type="text" name="rest" id="rest">
            </div>

            <div class="set-time-box">
                <label for="break">Pauze</label>
                <input class="set-time-input" type="text" name="break" id="break">
            </div>

            <div class="set-time-box">
                <label for="poules">Poules <br>(kies een logisch aantal. Aantal teams <?=$teamscount?>)</label>
                <input class="set-time-input" type="number" name="poules" id="poules">
            </div>

            <button id="set-timebutton" type="submit">Registreer de vereisten</button>
        </form>
    </div>
</div>

<?php require 'footer.php';?>


