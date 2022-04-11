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
    <title>Movies</title>
</head>
<body>
    <section class="container">
    
    <section class="movie search">
    
    <form action="movie.php" method="get" class="search-input" style="background:#B33F45;">
        <label for="search"><a href="index.php"><img src="images/red-arrow.svg" class="svg"></a>Menu</label><a href="movie.php"><img src="images/video.svg" class="svg-video svg"></a>
            <div class="search-row">
                <input type="search" name="search-movie" id="input-search" placeholder="Titre/Genre/Distribution" class="input" autocomplete="off">
                <input type="number" style="width:30px;border: 1px solid black;width: 25px;border-radius: 8px;" name="resultat" placeholder="8" min="0" value="8" style="border-radius:14px;">
                <input type="submit" class="all-btn" value="ok" style="background:#B33F45;">
            </div>
    </form>
       
    <!-- REVIEW Select, filtre et pagination des films -->
    <?php
        if(isset($_GET["resultat"])){
            $resultatParPage = $_GET["resultat"];
        }else{
            $resultatParPage = 8;
        }
        $count = (int)$dbh->query('SELECT COUNT(id_film) FROM film')->fetch(PDO::FETCH_NUM)[0];
        
        if(isset($_GET["page"]) AND !empty($_GET["page"])){
            $pageCourante = intval($_GET["page"]);
        }else{
            $pageCourante = 1;
        }
        $depart = ($pageCourante-1)*$resultatParPage;
        $pageTotale = ceil($count/$resultatParPage);
        $nombrePage = $pageTotale / $resultatParPage;
        ?>
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
                    
                    if(td.length < 3){
                        console.log("oui");
                        nextBtn.style.visibility = "hidden";
                    }
                });
                    
            </script>                       
        <?php 
            }

        // REVIEW Select et filtre
        if(isset($_GET["search-movie"])){
            $searchq = $_GET["search-movie"];
            $query = $dbh->query("SELECT id_film, substr(film.titre,1,15) as 'titre', genre.nom as 'nom-genre', substr(distrib.nom,1,14) as 'nom-distrib' FROM `film` INNER JOIN `genre` ON film.id_genre=genre.id_genre INNER JOIN distrib ON distrib.id_distrib = film.id_distrib  Where titre like '$searchq%' OR genre.nom like '$searchq%' OR distrib.nom like '$searchq%' limit $depart,$resultatParPage");
                echo "<table border='1'>
            <tr>
            <th><u>Titre</u></th>
            <th><u>Genre</u></th>
            <th><u>Distribution</u></th>
            </tr>";

            while($film = $query->fetch()){
                echo "<tr>";
                echo "<td>" . $film['titre'] . "</td>";
                echo "<td>" . $film['nom-genre'] . "</td>";
                echo "<td>" . $film['nom-distrib'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }else{
            $query = $dbh->query("SELECT id_film, substr(film.titre,1,15) as 'titre', genre.nom as 'nom-genre', substr(distrib.nom,1,14) as 'nom-distrib' FROM `film` INNER JOIN `genre` ON film.id_genre=genre.id_genre INNER JOIN distrib ON distrib.id_distrib = film.id_distrib limit $depart,$resultatParPage");
                echo "<table border='1'>
            <tr>
            <th><u>Titre</u></th>
            <th><u>Genre</u></th>
            <th><u>Distribution</u></th>
            </tr>";

            while($film = $query->fetch()){
                echo "<tr>";
                echo "<td>" . $film['titre'] . "</td>";
                echo "<td>" . $film['nom-genre'] . "</td>";
                echo "<td>" . $film['nom-distrib'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
            ?>
            <div class="button">
                <?php
                    if(isset($_GET["search-movie"])){
                    ?>
                    <div><a class="prev bottom-btn" style="background:#B33F45;" href="?resultat=<?=$resultatParPage?>&search-movie=<?= $searchq ?>&page=<?= $pageCourante-1  ?>"></a></div>
                    <div><a class="next bottom-btn" style="background:#B33F45;" href="?resultat=<?=$resultatParPage?>&search-movie=<?= $searchq ?>&page=<?= $pageCourante+1  ?>"></a></div> 
                <?php
                    }else{
                ?>      
                    <div><a class="prev bottom-btn" style="background:#B33F45;" href="?resultat=<?=$resultatParPage?>&page=<?= $pageCourante-1  ?>"></a></div>
                    <div class='page'><?php echo $pageCourante."/".$pageTotale ?></div>
                    <div><a class="next bottom-btn" style="background:#B33F45;" href="?resultat=<?=$resultatParPage?>&page=<?= $pageCourante+1  ?>"></a></div> 
                    <?php
                    }
            ?>
            </div>  
    </section>
    <section class="avis">
    <form action="movie.php" method="post" style="margin-bottom: 50px;margin-top: 20px;">
            <label for="projection">Rechercher un film par date de projection</label>
            <input type="text" name="projection" class="input" placeholder="aaaa-mm-jj">
            <input type="submit" class="all-btn" value="ok">
        </form>
        <!-- REVIEW Rechercher par date de projection -->
        <?php
            if(isset($_POST["projection"])){
                $date = $_POST["projection"];
                $query = $dbh->query("SELECT * from film where date_debut_affiche = '$date' limit 5");
                $query->execute();
        
                echo "<table>";
                echo "<tr><th>titre</><th>date</th></tr>";
                while($date = $query->fetch()){
                    echo "<tr>";
                    echo "<td>" . $date['titre'] . "</td>";
                    echo "<td>" . $date['date_debut_affiche'] . "</td>";          
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>

        <form action="movie.php" method="post">
            <label for="avis">Ajouter un avis</label><br>
            <input type="text" name="id_membre" class="input" style="margin-bottom:7px;" placeholder="id du membre">
            <input type="text" name="id_film" class="input" style="margin-bottom:7px;" placeholder="id du film"><br>
            <textarea type="text" name="avis" class="input" rows="3" width="100" placeholder="le film est.."></textarea>
            <input type="submit" class="all-btn" id="goto" name="btn" style="transform: translateY(-6px);background:#B33F45;" value="ok">
        </form>
        <!-- REVIEW Ajouter un avis -->
        <?php

            $stmt = $dbh->prepare("ALTER TABLE historique_membre ADD avis VARCHAR (255) NOT NULL");
            $stmt->execute();
            if(isset($_POST["id_membre"]) && isset($_POST["id_film"])){
                $stmt = $dbh->prepare("UPDATE historique_membre set avis = :avis where id_membre = :id_membre AND id_film = :id_film");
                $stmt->bindParam(":avis",$_POST["avis"],PDO::PARAM_STR);
                $stmt->bindParam(":id_membre",$_POST["id_membre"],PDO::PARAM_INT);
                $stmt->bindParam(":id_film",$_POST["id_film"],PDO::PARAM_INT);
                $stmt->execute();
                $query = $dbh->query("SELECT * from historique_membre order by avis DESC limit 7");
    
                echo '<div class="voir-historique" id="voir-historique">';
                echo '<label for="voir-historique" style="background:#B33F45" id="label-historique">Derniers avis</label>';
                        
                echo "<table border='1' id='table-2'>
                            <tr>
                            <th><u>id</u></th>
                            <th><u>id_film</u></th>
                            <th><u>avis</u></th>
                            </tr>";
    
                while($reponse = $query->fetch()){
                    echo "<tr>";
                    echo "<td>" . $reponse['id_membre'] . "</td>";
                    echo "<td>" . $reponse['id_film'] . "</td>";
                    echo "<td>" . $reponse['avis'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            }
        ?>
    </section>
    <?php
        // NOTE js for the scroll to bottom effect
        if(isset($_POST["btn"])){
            ?>
            <script>
            const button = document.getElementById('goto')
            const target = document.getElementById('voir-historique')

            function bottom(){
                target.scrollIntoView({
                    block: 'center',
                    behavior: 'smooth'
                });
            }bottom()
            
            </script>
            <?php
        }
        ?>
</body>
</html>