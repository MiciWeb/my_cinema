<?php
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=cinema;charsetutf8', "root", "root");
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cinema | Micipsa Sersour</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="container">
        <div class="svg-container">

            <div class="svg-video-container">
                <a href="movie.php"><img src="images/video2.svg" class="svg-video-js svg-js"></a>
                <h3 style="color:#B33F45;">Films</h3>
            </div>

            <div class="svg-member-container">
                <a href="member.php"><img src="images/membership2.svg" class="svg-member-js svg-js"></a>
                <h3 style="color:#1D97B2;">Membres</h3>
            </div>

        </div>

        <div class="svg-admin-container">
            <a href="admin.php"><img src="images/settings.svg" class="svg-admin-js svg-js"></a>
            <h3>&nbsp;Admin</h3>
         </div>

    </section>

    
</body>
</html>