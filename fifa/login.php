<?php
/*
 * hier komt een form waar mensen kunnen inloggen als ze een account hebben
 */
require 'header.php';

?>
<div class="container">
    <form class="login-form" action="controller.php" method="post">
        <input type="hidden" name="type" value="login">

            <input class="login-input" type="username" name="username" id="username" placeholder="Gebruikersnaam">

            <input class="login-input" type="password" name="password" id="password" placeholder="Wachtwoord">

            <input id="login-submit" type="submit" value="login">

    </form>
</div>

<?php require 'footer.php'; ?>
