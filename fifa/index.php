<?php
require 'header.php';

/*hier moet de home pagina komen waar mensen kunnen inloggen registeren of iets downloaden.*/

if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){
    header('location: dashboard.php');
}
?>
<div class="container">
   <div class="height-page">
        <div class="title-flex">
            <h1>FIFA Organisatie</h1>
            <p class="info_homepage">Op deze website kan je een overzicht zien van de wedstrijden die spelen op een toernooi.
                Het houd bij welke teams tegen elkaar moeten en de scores per team. Wilt u hier ook gebruik van maken
                <a href="register.php">registreer</a>  of <a href="login.php">login!</a></p>
            <img id="soccerplayer" src="img/5.png" alt="">
        </div>
   </div>
</div>

<?php require 'footer.php'; ?>
