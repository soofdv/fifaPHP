<?php
session_start();
session_destroy();

header("location: index.php");
//als je op logout klikt als je ingelogd bent kom je op deze pagina begitn ie weer een session
// en destroyed die em gelijk waar door je naar de index pagina gestuurd word.
?>