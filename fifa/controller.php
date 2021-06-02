<?php
require 'config.php';

/*CHECKS IF THERE IS ANY USE OF A POST FUNCTION*/
//checkt of er iets van een 'post' manier is gebruikt anders word je weer terug gestuurd naar de index.

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ) {
    header('location: index.php');
    exit;
}



        /*REGISTER FUNCTION*/
if ($_POST['type'] === 'register'){//kijkt ie of de value en name bij elkaar horen van de post methode
    $username = htmlentities(trim($_POST['username']));
    $password1 = htmlentities(trim($_POST['password']));
    $password2 = htmlentities(trim($_POST['password_confirm']));

    $uppercase = PREG_MATCH('@[A-Z]@', $password1);
    $lowercase = PREG_MATCH('[@a-z]', $password1);
    $number = PREG_MATCH('[@0-9]', $password1);

    $user_check_query = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $user_check_query->execute([$username]);
    $users = $user_check_query->fetch();
    if ($users){//Als het gekozen gebruikers naam al bestaat dan krijg je deze fout melding als dat niet zo is dan word die goed gekeurd en in de database gezet.
        ?>
        <script type="text/javascript">
            alert("Sorry,deze gebruikersnaam is al in gebruik");
            window.location.href = "register.php";
        </script>
        <?php
    }
    else{
        if($password1 === $password2)//word gekeken of de wachtwoorden overeen komen.
        {
            if (!strlen($password1 < 7) && !$uppercase && !$lowercase && !$number === true){//als de wachtwoorden hier niet aan voldoen krijg je een foutmeldin
                ?>
                <script type="text/javascript">
                    alert("Sorry, je wachtwoor moet minimaal 7 karakters lang zijn, een hoofdletterbevatten, een getal en/of symbolen.");
                    window.location.href = "register.php";
                </script>
                <?php
            }else {
                $passwordHash = password_hash($password1, PASSWORD_DEFAULT);
                /*als alles klopt wordt het wachtwoord gehashd en dat is ook wat uiteindelijk in de database gezet word*/

                $sql = "INSERT INTO users (username, password)
                    VALUES (:username, :passwordHash)";
                $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
                $prepare->execute([
                    ':username' => $username,
                    ':passwordHash' => $passwordHash
                ]);

                $msg = "gebruiker is succesvol toegevoegd!";

                header("location: login.php?message=$msg");
                exit;
            }
        }
        else//als de wachtwoorden dus niet overeen komen krijgen ze deze melding
        {
            ?>
            <script type="text/javascript">
                alert("Wachtwoorden komen niet overeen");
                window.location.href = "register.php";
            </script>
            <?php
        }
    }
}

        /*LOGIN FUNCTION*/
if ( $_POST['type'] === 'login' ){//kijkt ie of de value en name bij elkaar horen van de post methode
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user_login_username_check_query = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $user_login_username_check_query->execute([$username]);
    $users = $user_login_username_check_query->fetch();


    if ($users) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            ':username' => $username
        ]);
        $users = $prepare->fetch(PDO::FETCH_ASSOC);//hier haalt ie alle usernames op van de users tabel

        $inputPassword = trim($_POST['password']);//hier kijkt ie naar het ingevulde wachtwoord en trimt ie het(haalt spaties weg etc.)

        if ($users) {
            $validPassword = password_verify($inputPassword, $users['password']);//hier checkt het of de ingevuld pas word het zelfde is als de password die in de database staat
            if ($validPassword) {
                session_start();
                $_SESSION["loggedin"] = true; //als het waar is dat je ingelogd bent dan pas begint er een session
                $_SESSION["id"] = $users["id"]; //session id moet gelijk zijn aan de users id

                if ($users['admin'] !== null)//als de colom van admin in users niet leeg is bijvoorbeeld 1 dan word je ingelogd als admin
                {
                    $_SESSION['admin'] = 1;
                    header("location: admin.php");
                    exit;
                }
                header("location: dashboard.php");//anders word je naar het dashboard gestuurd aka een gewone gebruiker zonder admin rechten
            } else {//als je een wachtwoord invult dat niet correct is dan krijg je deze foutmelding
                ?>
                <script type="text/javascript">
                    alert("verkeerde wachtwoord probeer opnieuw");
                    window.location.href = "login.php";
                </script>
                <?php
            }
        }
    } else {//als je niks invult dan krijg je deze foutmelding
        ?>
        <script type="text/javascript">
            alert("gebruikers naam niet gevonden");
            window.location.href = "login.php";
        </script>
        <?php
    }
    exit;
}
/*ADD TEAM FUNCTION*/
if ($_POST['type'] === 'addteam'){//kijkt ie of de value en name bij elkaar horen van de post methode


    $sql = "SELECT * FROM users WHERE id = :id ";
    $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
    $prepare->execute([
        ':id' => $_SESSION["id"]
    ]);
    $user = $prepare->fetch(PDO::FETCH_ASSOC);//haalt op welke user bezig is met een team aanmaken.

    $teamname = htmlentities($_POST['teamname']);
    $teamleader = htmlentities($_POST['teamleader']);
    $participants = htmlentities($_POST['participants']);
    $creator = $user['username'];
    //allemaal variablen die ingevuld moeten worden in een form die later in de database gezet moetworden.

    if (!$teamname || !$teamleader || !$participants  = true){//als je de velden niet hebt in gevuld dan kan je geen team aanmaken en krijg je een foutmelding.
        ?>
        <script type="text/javascript">
            alert("Je moet alle velden ingevuld hebben");
            window.location.href = "addteam.php";
        </script>
        <?php
    }else {
        $sql = "INSERT INTO teams (teamname, teamleader, participants, creator) VALUES (:teamname, :teamleader, :participants, :creator)";

        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            ':teamname' => $teamname,
            ':teamleader' => $teamleader,
            ':participants' => $participants,
            ':creator' => $creator

        ]);//hier word alle ingevulde informatie in de database gezet


        $msg = "Team is succesvol toegevoegd!";

        header("location: dashboard.php?message=$msg");
        exit;
    }
}


        /*DELETE TEAM FUNCTION*/
if ( $_POST['type'] === 'delete'){//kijkt ie of de value en name bij elkaar horen van de post methode
    $id = $_GET['id'];

    $sql = " DELETE from teams WHERE id = :id";
    $prepare =  $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
    $prepare->execute([
        ':id' => $id
    ]);//selecteerd de bepaalde team id die je wilt verwijderen en verwijderd het gelijk uit de teams tabel.

    header("location: admin.php?");
    exit;
}


        /*EDIT  TEAM FUNCTION*/

if ( $_POST['type'] === 'edit'){//kijkt ie of de value en name bij elkaar horen van de post methode


    $id = $_GET['id'];//pakt de juiste id van de team die je wilt gaan editen

    $teamname = $_POST['teamname'];
    $teamleader = $_POST['teamleader'];
    $participants = $_POST['participants'];
    $teamscore = $_POST['teamscore'];


    $sql = "UPDATE teams SET teamname= :teamname, teamleader= :teamleader, participants= :participants, teamscore= :teamscore WHERE id= :id";

    $prepare =  $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
    $prepare->execute([

        'teamname'      => $teamname,
        'teamleader'    => $teamleader,
        'participants'  => $participants,
        'teamscore'    => $teamscore,

        ':id' => $id

    ]);//zet het upgedate data terug in de juiste rij van de teams tabel
    header("location: dashboard.php");
    exit;

}





            /*MATCH MAKING FUNCTION*/

if ($_POST['type'] == 'create-match'){//kijkt ie of de value en name bij elkaar horen van de post methode

    $sqlreset = "TRUNCATE TABLE match_requirements";
    $querydel = $pdo->query($sqlreset); //verzoek naar de database, voer sql van hierboven uit en reset de hele tabel


    $start_time = $_POST['start_time'];
    $match_length = htmlentities(trim($_POST['match_length']));
    $rest = htmlentities(trim($_POST['rest']));
    $break = htmlentities(trim($_POST['break']));
    $poules = htmlentities($_POST['poules']);
    //variabelen die op gepakt moeten worden om in de match_requirements moeten komen

    if (!$start_time && !$match_length && !$rest && !$break && !$poules = true){//als de velden niet in gevuld zijn komt deze foutmelding.
        ?>
        <script type="text/javascript">
            alert("Er kan geen wedstrijd ingevuld zijn als niks is in gevuld.");
            window.location.href = "set_time.php";
        </script>
        <?php
    }else {
        $sql = "INSERT INTO match_requirements (start_time, match_length, rest, break, poules) VALUES (:start_time, :match_length, :rest, :break, :poules)";

        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            'start_time' => $start_time,
            'match_length' => $match_length,
            'rest' => $rest,
            'break' => $break,
            'poules' => $poules
        ]);
        //alle ingevulde vlakken worden in de match_requirements tabel gezet.

        $sqlreset = "TRUNCATE TABLE matches";
        $querydel = $pdo->query($sqlreset); //verzoek naar de database, voer sql van hierboven uit en reset de hele tabel


        $teamsql = "SELECT * FROM teams";
        $query = $pdo->query($teamsql); //verzoek naar de database, voer sql van hierboven uit
        $teams = $query->fetchAll(PDO::FETCH_ASSOC); //multie demensionale array //alles binnenhalen


        $fieldssql = "SELECT * FROM fields";
        $query = $pdo->query($fieldssql);//verzoek naar de database, voer sql van hierboven uit
        $fields = $query->fetchall(PDO::FETCH_ASSOC);//multie demensionale array //alles binnenhalen

        $timesql = "SELECT *FROM match_requirements";
        $query = $pdo->query($timesql);//verzoek naar de database, voer sql van hierboven uit
        $match_requirements = $query->fetchall(PDO::FETCH_ASSOC);//multie demensionale array //alles binnenhalen

        $timesql = "SELECT *FROM referees";
        $query = $pdo->query($timesql);//verzoek naar de database, voer sql van hierboven uit
        $referees = $query->fetchall(PDO::FETCH_ASSOC);//multie demensionale array //alles binnenhalen


        foreach ($match_requirements as $match_requirement) {

            $yourdatetime = $match_requirement['start_time'];/*Pakt de tijd uit de data base in een 'time' format*/
            $minutes = $match_requirement['match_length'] + $match_requirement['rest'] + $match_requirement['break'];
            /*Hier bereken ik de aantal minuten tot de andere wedstrijd (aantal minuten van de wedstrijd tussenpauze en pauze*/

            $length_match = $match_requirement['match_length'];
            $length_rest = $match_requirement['rest'];
            $length_break = $match_requirement['break'];

            $poules_requirements = $match_requirement['poules'];
            $timestamp = strtotime($yourdatetime) + $minutes * 60;
        }
        $fieldcount = count($fields);//hier pakt ie alle velden in 'fields' tabel
        $refereecount = count($referees);//hier pakt ie alle scheidsrechters in 'referees' tabel

        for ($i = 0; $i < $fieldcount; $i++) {
            $allfields[$i] = $fields[$i]['fieldname'];
        }//een loop om alle namen dan daadwerkelijk in een array zetten tot alle namen geweest zijn

        for ($q = 0; $q < $refereecount; $q++) {
            $allreferees[$q] = $referees[$q]['referee_name'];
        }//een loop om alle namen dan daadwerkelijk in een array zetten tot alle namen geweest zijn

        $teamsArray = array();//hier maak je een array aan om alle teamnames in te zetten
        $match_number = 0;
        $field_counter = 0;
        //de counters op 0 zetten zo dat de loop zometeen goed loopt

        foreach ($teams as $team) {
            array_push($teamsArray, $team['teamname']);
        }//alle teamnamen worden in de teamsArray gepusht zodat ze in een array komen te staan


        $arrLength = count($teamsArray);// hier word geteld hoeveel teams er dan in de array zitten


        /* Van de admin wordt met deze code nog verwacht dat hij/zij een logische afweging maakt voor het aantal poules*/
        /* Dus bij 8 teams zouden 1, 2 of 4 poules kunnen, maar geen 3*/
        /* Dus bij 15 teams zouden 1, 3 of 5 poules kunnen, maar geen 4*/
        /* En bij 3,5,7,11,13,17,19 teams kan alleen 1 poule  of er moet een team afvallen, want bv. bij 16 heb je juist weer heel veel mogelijkheden*/
        /* De afvanging hiervoor kan later ingebouwd worden, maar is in deze fase nog zeker niet nodig*/

        $poule_number = $poules_requirements;                                   /* Inlezen uit infoblokje admin*/
        $poule_size = $arrLength / $poule_number;                               /* Aantal teams gedeeld door het aantal poules*/
        $position_array_vertical = 0;                                           /* Houdt de plaats bij in de teams array voor de verticale as van het schema*/
        $position_array_horizontal = 0;                                         /* Houdt de plaats bij in de teams array voor de horizontale as van het schema*/
        $f = -1;                                                                /* Klaar zetten teller voor fields*/
        $s = -1;                                                                /* Klaar zetten teller voor referees*/
        /*$poule_name;                                                          /* Nog toe te voegen aan tabel*/

        for ($poule_number_counter = 1; $poule_number_counter <= $poule_number; $poule_number_counter++){
            //begint met tellen bij 1 om het makkelijk te houden en dan word er gekeken of de counter kleiner of gelijk is aan de poule nummer
            //en anders doet hij telkens er 1 bij.
            for ($poule_size_counter_ver = 1; $poule_size_counter_ver <= $poule_size; $poule_size_counter_ver++){
                //begint met tellen bij 1 om het makkelijk te houden en dan word er gekeken of de counter kleiner of gelijk is aan de poule grote
                //en anders doet hij telkens er 1 bij voor de verticale kant van de wedstrijd tabel als je in een tabel vorm denkt. zelfde doet ie voor de horizontale kant.
                $position_array_vertical = $position_array_vertical + 1;
                $position_array_horizontal = $position_array_vertical;
                for ($poule_size_counter_hor = $poule_size_counter_ver + 1; $poule_size_counter_hor <= $poule_size; $poule_size_counter_hor++) {
                    $position_array_horizontal = $position_array_horizontal + 1;

                    if ($teamsArray[$position_array_vertical] === $teamsArray[$position_array_horizontal]){
                        //een dubbel check dat ie nooit tegen zichzelf komt te spelen
                    } else {//dit is de counter loop voor de juist veld toewijzen
                        if ($fieldcount - 1 <= $f){
                            $f = 0;
                        } else {
                            $f++;
                        }
                        $currentfield = $allfields[$f];// zet alles in een array

                        if ($refereecount - 1 <= $s){//dit is de counter loop voor de juist scheids toewijzen
                            $s = 0;
                        } else {
                            $s++;
                        }
                        $current_referee = $allreferees[$s];// zet alles in een array


                        $matchsql = "INSERT INTO matches ( poule, team1, team2, length_match, length_rest, length_break ,field_id, referee )
                                values (:poule, :team1, :team2, :length_match, :length_rest, :length_break ,:field_id, :referee) ";
                        $prepare = $pdo->prepare($matchsql);//verzoek naar de database, voer sql van hierboven uit
                        $prepare->execute([
                            ':poule' => $poule_number_counter,
                            ':team1' => $teamsArray[$position_array_vertical - 1],// -1 doen om het weer de nulde element te maken.
                            ':team2' => $teamsArray[$position_array_horizontal - 1],
                            ':length_match' => $length_match,
                            ':length_rest' => $length_rest,
                            ':length_break' => $length_break,
                            ':field_id' => $currentfield,
                            ':referee' => $current_referee
                        ]);//zet alle informatie in de 'matches' tabel dat vervolgens ook te zien is met de informatie op de matches.php pagina
                    }
                }

            }
        }

        //exit;
        header("Location: matches.php");
        exit;
    }
}




    /*ADD SCORES FUNCTION*/

    if ($_POST['type'] === 'scores'){//kijkt ie of de value en name bij elkaar horen van de post methode

        $id = $_GET['id'];

        $team1_score = $_POST['team1_score'];
        $team2_score = $_POST['team2_score'];

        $matchessql = "SELECT * FROM matches";
        $query = $pdo->query($matchessql); //verzoek naar de database, voer sql van hierboven uit
        $matches = $query->fetchAll(PDO::FETCH_ASSOC);//multie demensionale array //alles binnenhalen

        $teamsql = "SELECT * FROM teams";
        $query = $pdo->query($teamsql); //verzoek naar de database, voer sql van hierboven uit
        $teams = $query->fetchAll(PDO::FETCH_ASSOC);//multie demensionale array //alles binnenhalen

        $sql = "UPDATE matches SET team1_score= :team1_score, team2_score= :team2_score WHERE id= :id";

        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([

            'team1_score' => $team1_score,
            'team2_score' => $team2_score,

            ':id' => $id

        ]);

        /* een attempt om de punten telling in de teams tabel automatisch te krijgen is niet gelukt.
        foreach ($teams as $team) {
            $teamname = $team['teamname'];
            $emptyteamscore = $team['teamscore'];
        }

        foreach ($matches as $match) {
            $teams1 = $match['team1'];
            $team1[] = $teams1;
        }

        $matchcount = count($matches);

        for ($i = 0; $i < $matchcount; $i++) {
            $allmatches[$i] = $matches[$i]['team1'];
        }


        $teamname = $team1;


        if ($team1_score > $team2_score) {
            $teamscore = $emptyteamscore + 3;

            $sql = "UPDATE teams SET teamscore= :teamscore WHERE id =:id ";
            $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
            $prepare->execute([
                'teamscore' => $teamscore,
                'id' => $team1['id']
            ]);

        }
        if ($team1_score === $team2_score) {
            $score1 = $team1_score + 1;
            $score1 = $team2_score + 1;
        }*/
        header("location: matches.php");
        exit;
    }


    /*ADD FIELD FUNCTION*/

    if ($_POST['type'] === 'addfield'){//kijkt ie of de value en name bij elkaar horen van de post methode

        $fieldname = htmlentities(trim($_POST['fieldname']));
        // hier worden de namen van de teams getrimt en gecheckt op html entities en letterlijk als tekst in de database gezet.


        $sql = "INSERT INTO fields (fieldname) VALUES (:fieldname)";

        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            ':fieldname' => $fieldname
        ]);
        //hier word alles in de 'fields' tabel gezet

        $msg = "Veld is succesvol toegevoegd!";

        header("location: fields.php?message=$msg");
        exit;
    }


    /*DELETE FIELD FUNCTION*/
    if ($_POST['type'] === 'delete_field') {
        $id = $_GET['id'];

        $sql = " DELETE from fields WHERE id = :id";
        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            ':id' => $id
        ]);
        // de juiste veld word verwijderd uit de database

        header("location: admin.php");
        exit;
    }


    /*RESET FIELDS DATABASE ID AI TO 1*/

    if ($_POST['type'] === 'reset_fields'){//kijkt ie of de value en name bij elkaar horen van de post methode


        $sql = "TRUNCATE TABLE fields";
        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            ':id' => $id
        ]);
        //alle velden worden verwijderd en de id begint weer bij 1 te tellen
        header("location: admin.php");
        exit;
    }


    /*ADD REFEREE FUNCTION*/

    if ($_POST['type'] === 'addreferee'){//kijkt ie of de value en name bij elkaar horen van de post methode

        $referee_name = htmlentities(trim($_POST['referee_name']));
        // hier worden de namen van de teams getrimt en gecheckt op html entities en letterlijk als tekst in de database gezet.


        $sql = "INSERT INTO referees (referee_name) VALUES (:referee_name)";

        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            ':referee_name' => $referee_name

        ]);
        //de naam word in de 'referees' tabel gezet

        $msg = "Scheidsrechter is succesvol toegevoegd!";

        header("location: referees.php?message=$msg");
        exit;
    }


    /*RESET REFEREES TABEL FUNCTION*/
    if ($_POST['type'] === 'reset_referees'){//kijkt ie of de value en name bij elkaar horen van de post methode


        $sql = "TRUNCATE TABLE referees";
        $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
        $prepare->execute([
            ':id' => $id
        ]);
        //alles in de tabel word verwijderd en id begint weer bij 1
        header("location: admin.php");
        exit;
    }

    /*DELETE REFEREE FUNCTION*/
if ($_POST['type'] === 'delete_referee'){//kijkt ie of de value en name bij elkaar horen van de post methode
    $id = $_GET['id'];

    $sql = " DELETE from referees WHERE id = :id";
    $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
    $prepare->execute([
        ':id' => $id
    ]);
    //de geselecteerde scheids word verwijderd uit 'referees' tabel

    header("location: admin.php");
    exit;
}

        /*DELETE USER FUNCTION*/
if ($_POST['type'] === 'delete_user'){//kijkt ie of de value en name bij elkaar horen van de post methode
    $id = $_GET['id'];

    $sql = " DELETE from users WHERE id = :id";
    $prepare = $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
    $prepare->execute([
        ':id' => $id
    ]);
    //de geselecteerde scheids word verwijderd uit 'referees' tabel

    header("location: admin.php");
    exit;
}

        /*EDIT USER FUNCTION*/

if ( $_POST['type'] === 'edit_user'){//kijkt ie of de value en name bij elkaar horen van de post methode


    $id = $_GET['id'];//pakt de juiste id van de team die je wilt gaan editen

    $admin = $_POST['admin'];


    $sql = "UPDATE users SET admin= :admin WHERE id= :id";

    $prepare =  $pdo->prepare($sql);//verzoek naar de database, voer sql van hierboven uit
    $prepare->execute([

        'admin'      => $admin,

        ':id' => $id

    ]);//zet het upgedate data terug in de juiste rij van de teams tabel
    header("location: users.php");
    exit;

}