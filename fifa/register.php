<?php
/*
 *hier moeten mensen kunnen registeren om een account aan te maken om teams te kunnen maken
 */
require 'header.php';
?>
<div class="container">
    <div class="height-page">
        <form class="register-form" action="controller.php" method="post">

            <input type="hidden" name="type" value="register">

            <input class="register-input" type="username" name="username" id="username" placeholder="gebruikersnaam">

            <input class="register-input" type="password" name="password" id="password" required placeholder="wachtwoord">

            <input class="register-input" type="password" name="password_confirm" id="password_confirm" required minlength="7" placeholder="Bevestig wachtwoord">

            <input id="register-submit" type="submit" value="Register">
        </form>
    </div>
</div>

<?php require 'footer.php'; ?>