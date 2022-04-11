<!-- REVIEW Connexion à la base de données -->
<?php
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=cinema;charsetutf8', "root", "root");
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="container">
    <section class="add-profil">
        <div class="profil">
        <a href="index.php" style="position:absolute;"><img class="left"src="images/up-arrow.svg" alt=""></a>
            <div class="top">
            <h3>Ajouter un profil</h3>
            </div>
            <div class="infos">
                <form action="admin.php" method="post" class="form-add">
                    <input type="text" autocomplete="off" name="nom" placeholder="nom" autocomplete="off">
                    <input type="text" autocomplete="off" name="prenom" placeholder="prenom">
                    <input type="text" autocomplete="off" name="email" placeholder="email">
                    <input type="text" autocomplete="off" name="adresse" placeholder="adresse">
                    <input type="text" autocomplete="off" name="code" placeholder="code postale">
                    <input type="text" autocomplete="off" name="ville" placeholder="ville">
                    <input type="text" autocomplete="off" name="pays" placeholder="pays">
                    <input type="submit" value="ok" class="all-btn" style="width:40px;transform: translate(97px, 5px);">
                </form>
            </div>
        </div>
    </section>
        <?php
            $query = $dbh->query("SELECT id_perso from fiche_personne order by id_perso desc limit 1");
            $row = $query->fetch();
            $auto_increment = $row["id_perso"] +1;

            $stmt = $dbh->prepare("INSERT INTO fiche_personne(`id_perso`,`nom`, `prenom`, `date_naissance`, `email`, `adresse`, `cpostal`, `ville`, `pays`)
             VALUES (:id_perso,:nom,:prenom,now(),:email,:adresse,:code,:ville,:pays)");
            $stmt->bindParam(":id_perso",$auto_increment);
            $stmt->bindParam(":nom", $_POST["nom"]);
            $stmt->bindParam(":prenom", $_POST["prenom"]);
            $stmt->bindParam(":email", $_POST["email"]);
            $stmt->bindParam(":adresse", $_POST["adresse"]);
            $stmt->bindParam(":code", $_POST["code"]);
            $stmt->bindParam(":ville", $_POST["ville"]);
            $stmt->bindParam(":pays", $_POST["pays"]);
            $stmt->execute();
        ?>
    <section class="add-profil">
        <div class="profil">
            <div class="top">
            <h3>Programmer un film</h3>
            </div>
            <div class="infos">
                <form action="admin.php" method="post" class="form-add">
                    <input type="text" autocomplete="off" name="id-film" placeholder="id film" autocomplete="off">
                    <input type="text" autocomplete="off" name="id-salle" placeholder="id salle">
                    <input type="text" autocomplete="off" name="id-ouvreur" placeholder="id ouvreur">
                    <input type="text" autocomplete="off" name="id-technicien" placeholder="id technicien">
                    <input type="text" autocomplete="off" name="id-menage" placeholder="id menage">
                    <input type="text" autocomplete="off" name="debut" placeholder="Debut">
                    <input type="text" autocomplete="off" name="fin" placeholder="Fin">
                    <input type="submit" value="ok" class="all-btn" style="width:40px;transform: translate(97px, 5px);">
                </form>
            </div>
        </div>
    </section>
    <?php
            $stmt = $dbh->prepare("INSERT INTO grille_programme(`id_film`, `id_salle`, `id_fiche_perso_ouvreur`, `id_fiche_perso_technicien`, `id_fiche_perso_menage`, `debut_sceance`, `fin_sceance`)
             VALUES (:idfilm,:idsalle,:idouvreur,:idtech,:idmenage,:debut,:fin)");
            $stmt->bindParam(":idfilm", $_POST["id-film"]);
            $stmt->bindParam("idsalle", $_POST["id-salle"]);
            $stmt->bindParam("idouvreur", $_POST["id-ouvreur"]);
            $stmt->bindParam("idtech", $_POST["id-technicien"]);
            $stmt->bindParam("idmenage", $_POST["id-menage"]);
            $stmt->bindParam("debut", $_POST["debut"]);
            $stmt->bindParam("fin", $_POST["fin"]);
            $stmt->execute();
        ?>
    </section>
</body>
</html>