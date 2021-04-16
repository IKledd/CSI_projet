<?php
    require "../fonctions/bdd.php";
     
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Composer votre lot</title>
        <link rel="stylesheet" type="text/css" href="../styles/homePage.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
        <meta charset="utf-8"/>
    </head>
    <body>
        <div id="menuBar">
            <div class="menuButton">
                <legend><center>Nouveau produit</center></legend>
                <form method="get" action="">
                            
                    <label class="label_form" for="login_conn">Marque</label>
                    <input class="form_input" type="text" name="login_conn" id="login_conn"/>
                    <label class="label_form" for="login_conn">Prix initial</label>
                    <input class="form_input" type="text" name="login_conn" id="login_conn"/>
                    <select type="text" class="form-control" id="type_prod">
                        <?php/*
                            $bdd = Bdd::getBdd();
                            $sql ="SELECT select tprod_id, tprod_libelle from t_type_produit_tprod";
                            echo $sql;
                            $req=$bdd->prepare($sql);
                            $req->execute();
                            echo "<option selected disabled>Type du produit</option>";
                            while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['tprod_id'] . "'>";
                                echo "n°: ".$row['tprod_id']." : " .$row['tprod_libelle'];
                                echo "</option>";   
                            }*/
                        ?>
                    </select>
                    <input type="submit" value="Creer"/>
                </form>
            </div>
        </div>
        
        <div id="actions">
            <div class='jumbotron'>
                <legend><center>Sélectionner un lot en vente</center></legend>
                <form method="get" action="./lot_dynamique_gestionnaire.php?>" id="form_on_sale">
                    <select type="text" class="form-control" id="lot_on_sale" name="lot_on_sale" required onchange="document.getElementById('form_on_sale').submit();">
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
    </div>

    <div class='jumbotron'>
        <legend><center>Sélectionner un lot vendu</center></legend>
        <form method="get" action="./lot_dynamique_gestionnaire.php?>" id="form_sold">
            <select type="text" class="form-control" id="lot_sold" name="lot_sold" required onchange="document.getElementById('form_sold').submit();">
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
    </div>
        </div>

    

    <input type="button" value="Se déconnecter" onclick="javascript:location.href='./login_form.php'" class="btn btn-outline-danger">

    </body>
</html>