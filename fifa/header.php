<?php require 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FIFA</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&display=swap" rel="stylesheet">
</head>
<body>
<section>
    <div class="container">
        <div class="header-flex">
            <a href="index.php"><img id="logo" src="img/fifa_logo.png" alt="FIFA"></a>
            <div class="nav">
                <?php
                if ( isset($_SESSION['id']) ) {
                echo "You are currently logged in. Want to <a  href='logout.php'>&nbsp;Logout?</a>";
                } else {
                echo "<a  href='login.php'>Login</a> &nbsp;or&nbsp; <a href='register.php'> Register </a>";
                }
                ?>
<!--                hier kan je op inloggen, registeren klikken en op uitloggen als je ingelogd bent.-->
            </div>
        </div>
    </div>
</section>

