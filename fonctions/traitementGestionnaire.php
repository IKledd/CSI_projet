<?php
    require "./bdd.php";
    session_start();

    if (isset($_POST['marque']) && isset($_POST['prix']) && isset($_POST['type_prod'])) {
        $marque = $_POST['marque'];
        $prix = $_POST['prix'];
        $type = $_POST['type_prod'];
        $bdd = Bdd::getBdd();
        
         $sql ="call ajouter_produit('" .$marque. "', " .$prix. ", " .$type. ")";
        echo $sql;
        $req=$bdd->prepare($sql);
        $req->execute();

        header('Location: ../vues/general_gestionnaire');
    }
?>