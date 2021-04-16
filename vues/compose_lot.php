<?php
    require "../fonctions/bdd.php";
     try {
    $db = new PDO("pgsql:host=localhost;dbname=projet_CSI","postgres","root",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    echo "Connected to db :D";
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Composer votre lot</title>
        <link rel="stylesheet" type="text/css" href="../styles/homePage.css">
        <meta charset="utf-8"/>
    </head>
    <body>
        <div id="menuBar">
            <div class="menuButton"> Vendre
                
            </div>
            <div class="menuButton"> Rechercher
                
            </div>
            <div class="menuButton"> Autre
                
            </div>
            <div id="actualPage" class="menuButton"> Composer un lot
                
            </div>
        </div>
        
        <div id="actions">
            
        </div>


    <form method="get" action="./lot_dynamique_gestionnaire.php?>" id="form_on_sale">
        <p>Sélectionner un lot en vente</p>
        <select type="text" id="lot_on_sale" name="lot_on_sale" required onchange="document.getElementById('form_on_sale').submit();">
            <?php
                $bdd = Bdd::getBdd();
                $sql ="SELECT lot_id,lot_date_fin_vente FROM t_lot_lot where lot_etat='en vente'";
                echo $sql;
                $req=$bdd->prepare($sql);
                $req->execute();
                echo "<option selected disabled>Lots en vente</option>";
                while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['lot_id'] . "'>";
                    echo $row['lot_id']." disponible jusque : " .$row['lot_date_fin_vente'];
                    echo "</option>";   
                }
            ?>
        </select>
    </form>

    <form method="get" action="./lot_dynamique_gestionnaire.php?>" id="form_sold">
        <p>Sélectionner un lot vendu</p>
        <select type="text" id="lot_sold" name="lot_sold" required onchange="document.getElementById('form_sold').submit();">
            <?php
                $bdd = Bdd::getBdd();
                $sql ="SELECT lot_id,lot_prix_achat,lot_gagnant FROM t_lot_lot where lot_etat='gagne'";
                echo $sql;
                $req=$bdd->prepare($sql);
                $req->execute();
                echo "<option selected disabled>Lots vendus</option>";
                while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['lot_id'] . "'>";
                    echo $row['lot_id']." vendu pour : " .$row['lot_prix_achat'] . " à " . $row['lot_gagnant'];
                    echo "</option>";   
                }
            ?>
        </select>
    </form>

    </body>
</html>