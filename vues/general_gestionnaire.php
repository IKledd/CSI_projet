<?php
    require "../fonctions/bdd.php";
    require "../fonctions/statusGestionnaire.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Composer votre lot</title>
        <link rel="stylesheet" type="text/css" href="../styles/general_gestionnaire.css"> 
        <link rel="icon" type="image/png" href="./../images/favicon.png" />
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
        <div id="contain">
             <div class="jumbotron" id="new_product">
                <legend><center>Nouveau produit</center></legend>
                <form method="post" action="../fonctions/traitementGestionnaire.php">
                            
                    <label class="col-form-label" for="marque">Marque</label>
                    <input class="form-control" type="text" name="marque" id="marque"/>
                    <label class="col-form-label" for="prix">Prix initial</label>
                    <input class="form-control" type="number" step="0.01" min="0" name="prix" id="prix"/>
                    <label class="col-form-label" for="dateSortie">Date de sortie </label>
                    <input class="form-control" type="datetime-local" name="dateSortie" id="dateSortie"/><br>
                    <select type="text" class="form-control" name="type_prod" id="type_prod">
                        <?php
                            $bdd = Bdd::getBdd();
                            $sql ="SELECT tprod_id, tprod_libelle from t_type_produit_tprod";
                            echo $sql;
                            $req=$bdd->prepare($sql);
                            $req->execute();
                            echo "<option selected disabled>Type du produit</option>";
                            while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['tprod_id'] . "'>";
                                echo "n° ".$row['tprod_id']." : " .$row['tprod_libelle'];
                                echo "</option>";   
                            }
                        ?>
                    </select>
                    <input type="submit" class="btn btn-outline-success" value="Creer"/>
                </form>
            </div>
             <div class="jumbotron" id="add_product_lot">
                <legend><center>Ajouter un produit à un lot</center></legend>
                <form method="post" action="../fonctions/traitementGestionnaire.php">
                    <select type="text" class="form-control" name="choix_lot" id="choix_lot">
                        <?php
                            $bdd = Bdd::getBdd();
                            $sql ="SELECT distinct lot_id, lot_date_debut_vente, lot_date_fin_vente from t_lot_lot where lot_etat = 'en attente'";
                            echo $sql;
                            $req=$bdd->prepare($sql);
                            $req->execute();
                            echo "<option selected disabled>Choisissez votre lot</option>";
                            while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['lot_id'] . "'>";
                                echo "Lot n°".$row['lot_id']." - début: " .$row['lot_date_debut_vente']." - fin: " .$row['lot_date_fin_vente'];
                                echo "</option>";   
                            }
                        ?>
                    </select>
                    <br>
                    <select type="text" class="form-control" name="choix_produit" id="choix_produit">
                        <?php
                            $bdd = Bdd::getBdd();
                            $sql ="SELECT distinct on (prod_id) prod_id, prod_marque, prod_prix_initial, tprod_libelle from t_produit_prod as a, t_type_produit_tprod as b
where a.tprod_id = b.tprod_id";
                            echo $sql;
                            $req=$bdd->prepare($sql);
                            $req->execute();
                            echo "<option selected disabled>Choisissez votre produit</option>";
                            while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['prod_id'] . "'>";
                                echo "Produit n°".$row['prod_id']." - " .$row['prod_marque']." - " .$row['prod_prix_initial']."€ - " .$row['tprod_libelle'];
                                echo "</option>";   
                            }
                        ?>
                    </select>
                    <label class="label_form" for="quantite">Quantité</label>
                    <input class="form-control" type="number" step="1" min="0" name="quantite" id="quantite"/>
                    <input type="submit" class="btn btn-outline-success" value="Ajouter"/>
                </form>
            </div>
             <div class="jumbotron" id="new_lot">
                <?php
                    if (isset($_GET['dateerror']) && $_GET['dateerror']) {
                        echo "<span>Problème de choix dans la date</span></br>";
                    }
                    if (isset($_GET['priceerror']) && $_GET['priceerror']) {
                        echo "<span>Le prix estimé doit-être supérieur au prix minimal</span></br>";
                    }
                ?>
                <legend><center>Mettre en vente un lot</center></legend>
                <form method="post" action="../fonctions/traitementGestionnaire.php">
                            
                    <label class="label_form" for="prix_est">Prix estimé (supérieur au prix minimal)</label>
                    <input class="form-control" type="number" step="0.01" min="0" name="prix_est" id="prix_est"/>
                    <label class="label_form" for="prix_min">Prix minimal</label>
                    <input class="form-control" type="number" step="0.01" min="0" name="prix_min" id="prix_min"/>
                    <label class="label_form" for="dateDebut">Date de début </label>
                    <input class="form-control" type="datetime-local" name="dateDebut" id="dateDebut"/>
                    <label class="label_form" for="dateFin">Date de fin </label>
                    <input class="form-control" type="datetime-local" name="dateFin" id="dateFin"/>
                    <input type="submit" value="Créer" class="btn btn-outline-success"/>
                </form>
            </div>
        </div>
        
        <div id="actions">
            <div id="lot_on_sale">
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

    <div  id="lot_sold">
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
    <input type="button" value="Se déconnecter" onclick="javascript:location.href='./connexion.php'" class="btn btn-outline-danger">
        </div>

    

    

    </body>
</html>