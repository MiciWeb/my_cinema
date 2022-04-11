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
    <link rel="stylesheet" href="style.css">
    <title>Members</title>
</head>
<body>

    <section class="container">
        <section class="member search">
            <form action="member.php" method="GET" class="search-input" style="background:#1D97B2;">
            
                <label for="search"><a href="index.php"><img src="images/blue-arrow.svg" class="svg"></a>Menu</label><a href="member.php"><img src="images/membership.svg" class="svg-member svg"></a>
                <div class="search-row">
                    <input type="search" name="search-member" id="input-search" placeholder="Nom ou Prenom" autocomplete="off">
                    <input type="submit" class="all-btn" value="ok" style="background:#1D97B2;">
                </div>
            </form>
        <!-- REVIEW Select, filtre et pagination des membres -->
            <?php
                // REVIEW pagination
                $resultatParPage = 8;
                if(isset($_GET["search-member"])){
                    $searchq = $_GET["search-member"];
                    $count = (int)$dbh->query("SELECT COUNT(id_perso) FROM fiche_personne Where prenom like '$searchq%' OR nom like '$searchq%' OR concat(nom,' ',prenom) like '$searchq%' ")->fetch(PDO::FETCH_NUM)[0];
                }else{
                    $count = (int)$dbh->query("SELECT COUNT(id_perso) FROM fiche_personne")->fetch(PDO::FETCH_NUM)[0];
                }
                if(isset($_GET["page"]) AND !empty($_GET["page"])){
                    $pageCourante = intval($_GET["page"]);
                }else{
                    $pageCourante= 1;
                }
                $depart = ($pageCourante-1)*$resultatParPage;
                $pageTotale = ceil($count/$resultatParPage);
                $nombrePage = $pageTotale / $resultatParPage;
                ?>
                <!-- Js part -->
            <script>
                document.addEventListener('DOMContentLoaded',function(){
                let prevBtn = document.querySelector(".prev");
                });
                </script>
                <?php
                if($pageCourante<=1){
                        $pageCourante = 1;
                }
                    if($pageCourante == 1){
                ?>
                    <script>
                    document.addEventListener('DOMContentLoaded',function(){
                        let prevBtn = document.querySelector(".prev");
                        prevBtn.style.visibility = "hidden";
                    });
                    </script>
                <?php
                    }else{
                ?>
                    <script>
                        document.addEventListener('DOMContentLoaded',function(){
                            let nextBtn = document.querySelector(".next");
                            
                            let td = document.querySelectorAll("td");
                            
                            if(td.length < 32){
                                console.log("oui");
                                nextBtn.style.visibility = "hidden";
                            }
                        });
                            
                    </script>                       
                <?php 
                }
                // REVIEW Select et filtre
                if(isset($_GET["search-member"])){
                    $searchq = $_GET["search-member"];
                    $query = $dbh->query("SELECT membre.id_membre,membre.id_abo, concat(upper(substring(fiche_personne.nom,1,1)),substring(fiche_personne.nom,2,8)) as nom ,concat(upper(substring(fiche_personne.prenom,1,1)),substring(fiche_personne.prenom,2,8)) as prenom from fiche_personne INNER JOIN membre ON fiche_personne.id_perso = membre.id_fiche_perso Where prenom like '$searchq%' OR nom like '$searchq%' OR concat(nom,' ',prenom) like '$searchq%' order by id_membre limit $depart,$resultatParPage");
                    echo "<table>
                    <tr>
                    <th><u>Id</u></th>
                    <th><u>Nom</u></th>
                    <th><u>Prenom</u></th>
                    <th><u>N° abo</u></th>
                    </tr>";
                    while($fiche_personne = $query->fetch()){
                            echo "<tr>";
                            echo "<td>" . $fiche_personne['id_membre'] . "</td>";
                            echo "<td>" . $fiche_personne['nom'] . "</td>";
                            echo "<td>" . $fiche_personne['prenom'] . "</td>";
                            echo "<td>" . $fiche_personne['id_abo'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }else{
                        $query = $dbh->query("SELECT membre.id_membre,membre.id_abo, concat(upper(substring(fiche_personne.nom,1,1)),substring(fiche_personne.nom,2,8)) as nom ,concat(upper(substring(fiche_personne.prenom,1,1)),substring(fiche_personne.prenom,2,8)) as prenom from fiche_personne INNER JOIN membre ON fiche_personne.id_perso = membre.id_fiche_perso order by id_membre limit $depart,$resultatParPage");
                        echo "<table>
                        <tr>
                        <th><u>Id</u></th>
                        <th><u>Nom</u></th>
                        <th><u>Prenom</u></th>
                        <th><u>N° abo</u></th>
                        </tr>";
                        while($fiche_personne = $query->fetch()){
                                echo "<tr>";
                                echo "<td>" . $fiche_personne['id_membre'] . "</td>";
                                echo "<td>" . $fiche_personne['nom'] . "</td>";
                                echo "<td>" . $fiche_personne['prenom'] . "</td>";
                                echo "<td>" . $fiche_personne['id_abo'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                        ?>
            <div class="button">
                <?php
                    if(isset($_GET["search-member"])){
                ?>
                    <div><a class="prev bottom-btn" style="background:#1D97B2;" href="?search-member=<?= $searchq ?>&page=<?= $pageCourante-1  ?>"></a></div>
                    <div class='page'><?php echo $pageCourante."/".$pageTotale ?></div>
                    <div><a class="next bottom-btn" style="background:#1D97B2;" href="?search-member=<?= $searchq ?>&page=<?= $pageCourante+1  ?>"></a></div> 
                <?php
                    }else{
                ?>      
                    <div><a class="prev bottom-btn" style="background:#1D97B2;" href="?page=<?= $pageCourante-1  ?>"></a></div>
                    <div class='page'><?php echo $pageCourante."/".$pageTotale ?></div>
                    <div><a class="next bottom-btn" style="background:#1D97B2;" href="?page=<?= $pageCourante+1  ?>"></a></div> 
                    <?php
                    }
            ?>
            </div>  
        </section>

        <section class="abo">
            <div style="margin-top: 25px;">
                <form action="member.php" method="post">
                    <label for="add-abo">Ajouter ou modifier un abonnement<br></label>
                    <input type="text" name="add-perso" autocomplete="off" placeholder="id">
                    <input type="text" name="add-abo" autocomplete="off" placeholder="id_abo">
                    <input type="submit" class="all-btn" value="ok"><br>
                
                    <label for="del-abo">Supprimer un abonnement<br></label>
                    <input type="text" name="del-perso" autocomplete="off" placeholder="id">
                    <input type="submit" class="all-btn" value="ok">
                </form>
            </div>
            <!-- REVIEW Ajouter/modifier/supprimer l'abonnement d'un membre -->
            <?php
                if(isset($_POST["add-perso"]) && isset($_POST["add-abo"])){
                    $add = "UPDATE membre SET id_abo = :id_abo where id_membre = :id_membre";
                    $stmt = $dbh->prepare($add);
                    $stmt->bindParam(":id_abo", $_POST["add-abo"]);
                    $stmt->bindParam(":id_membre", $_POST["add-perso"]);
                    $stmt->execute();
                }
                if(isset($_POST["del-perso"])){
                    $del = "UPDATE membre SET id_abo = 0 where id_membre = :id_membre";
                    $stmt = $dbh->prepare($del);
                    $stmt->bindParam(":id_membre", $_POST["del-perso"]);
                    $stmt->execute();
                }
            ?>
            <div style="margin:40px 0;">
                <form action="member.php" method="post">
                <label for="historique">Afficher l'historique d'un membre <br></label>
                <input type="text" name="historique" autocomplete="off" placeholder="id">
                <input type="submit" class="all-btn" id="goto" name="btn" value="ok">
                
                </form>
                
                <form action="member.php" method="post">
                <label for="historique">Ajouter un film à l'historique d'un membre<br></label>
                <input type="text" name="historique" autocomplete="off" placeholder="id">
                <input type="text" name="add-movie" autocomplete="off" placeholder="id du film">
                <input type="submit" class="all-btn" value="ok">
                </form> 
            </div>
            <div id="table-2">
            <!-- REVIEW Afficher et ajouter un film à l'historique d'un membre -->
            <?php
                if(isset($_POST["historique"]) && isset($_POST["add-movie"])){
                    $stmt = $dbh->prepare('INSERT INTO historique_membre(id_membre,id_film,date,avis) VALUES(:id_membre, :id_film, NOW(), "") ');
                    $stmt->bindParam(":id_membre",$_POST["historique"],PDO::PARAM_INT);
                    $stmt->bindParam(":id_film",$_POST["add-movie"],PDO::PARAM_INT);
                    $stmt->execute();
                }
                    if(!empty($_POST["historique"])){
                        echo "<div class='bottom2'>";
                        $historique_membre = $_POST["historique"];
                        $historique_query = $dbh->query("SELECT concat(upper(substring(fiche_personne.nom,1,1)),substring(fiche_personne.nom,2,6)) as nom ,concat(upper(substring(fiche_personne.prenom,1,1)),substring(fiche_personne.prenom,2,6)) as prenom , film.id_film as id_film, substring(film.titre,1,17) as titre, substr(historique_membre.date,1,11) as date from historique_membre inner join film on historique_membre.id_film = film.id_film inner join membre on historique_membre.id_membre = membre.id_membre inner join fiche_personne on fiche_personne.id_perso = membre.id_fiche_perso where membre.id_membre = $historique_membre ORDER BY date DESC limit 9");
                        $historique_row = $historique_query->fetch();
                        echo '<div class="voir-historique">';
                        echo '<label for="voir-historique" id="label-historique">Historique de '.$historique_row["nom"]." ".$historique_row["prenom"].'</label>';
                        
                        echo "<table class='table-2' id='table-2'>
                        <tr>
                        <th><u>Id film</u></th>
                        <th><u>Titre</u></th>
                        <th><u>Date</u></th>
                        </tr>";           
                        
                        while($historique = $historique_query->fetch()){
                            echo "<tr>";
                            echo "<td>" . $historique['id_film'] . "</td>";
                            echo "<td>" . $historique['titre'] . "</td>";
                            echo "<td>" . $historique['date'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        ?>
                        <div class="button-2">
                            <?php
                                // if(isset($_GET["search-member"])){
                            ?>
                            <div class="arrow">
                                <a class="prev-2 bottom-btn" style="background:#33333;" href="?search-member=<?= $searchq ?>&page=<?= $pageCourante+1  ?>"></a> 
                                <!-- <div class='page'><?php echo $pageCourante."/".$pageTotale ?></div> -->
                                <div style="font-size:10px;padding-top:5px;margin:0 5px;">1/2</div>
                                <a class="next-2 bottom-btn" style="background:#33333;" href="?search-member=<?= $searchq ?>&page=<?= $pageCourante+1  ?>"></a> 
                            </div>
                           <?php
                                // }else{
                            ?>      
                                <!-- <div><a class="prev bottom-btn" style="background:#1D97B2;" href="?page=<?= $pageCourante-1  ?>"></a></div>
                                <div class='page'><?php echo $pageCourante."/".$pageTotale ?></div>
                                <div><a class="next bottom-btn" style="background:#1D97B2;" href="?page=<?= $pageCourante+1  ?>"></a></div>  -->
                                <?php
                                // }
                        ?>
                        </div>
                        <?php
                        echo "</div>";
                    }
            ?> 
           
        </section> 
    </section> 
    <?php
        // NOTE js for the scroll to bottom effect
        if(isset($_POST["btn"])){
            ?>
            <script>
            const button = document.getElementById('goto')
            const target = document.getElementById('table-2')

            function bottom(){
                target.scrollIntoView({
                    block: 'end',
                    behavior: 'smooth'
                });
            }bottom()
            
            </script>
            <?php
        }
        ?>
</body>
</html>