<?php
    require "./bdd.php";
    session_start();
    try  {
        if (isset($_POST['marque']) && isset($_POST['prix']) && isset($_POST['type_prod']) && isset($_POST['dateSortie'])) {
        $marque = $_POST['marque'];
        $prix = $_POST['prix'];
        $type = $_POST['type_prod'];
        $dateSortie = $_POST['dateSortie'];
        $bdd = Bdd::getBdd();
        
         $sql ="call ajouter_produit('" .$marque. "', " .$prix. ", " .$type. ", '" .$dateSortie. "')";
        echo $sql;
        $req=$bdd->prepare($sql);
        $req->execute();

        header('Location: ../vues/general_gestionnaire');
        }
    } catch (Exception $e){
        header('Location: ../vues/general_gestionnaire');
    }
    
    try {
        if (isset($_POST['choix_lot']) && isset($_POST['choix_produit']) && isset($_POST['quantite'])) {
        $choixLot = $_POST['choix_lot'];
        $choixProduit = $_POST['choix_produit'];
        $quantite = $_POST['quantite'];
        $bdd = Bdd::getBdd();
        
         $sql ="call ajouter_produit_a_un_lot('" .$choixLot. "', " .$choixProduit. ", " .$quantite. ")";
        echo $sql;
        $req=$bdd->prepare($sql);
        $req->execute();

        header('Location: ../vues/general_gestionnaire');
        }
    } catch (Exception $e){
        header('Location: ../vues/general_gestionnaire');
    }

    try{
        if (isset($_POST['prix_est']) && isset($_POST['prix_min']) && isset($_POST['dateDebut']) && isset($_POST['dateFin'])) {
            $prixEst = $_POST['prix_est'];
            $prixMin = $_POST['prix_min'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            if($dateDebut>$dateFin){
                header('Location: ../vues/general_gestionnaire?dateerror=true');
            }else if($prixEst<$prixMin){
                header('Location: ../vues/general_gestionnaire?priceerror=true');
            }else{
                
                $bdd = Bdd::getBdd();

                $sql ="call mise_en_vente_lot(" .$prixMin. ", " .$prixEst. ", '" .$dateFin. "', '" .$dateDebut. "')";
                echo $sql;
                $req=$bdd->prepare($sql);
                $req->execute();

                header('Location: ../vues/general_gestionnaire');
            }
            
        }
    } catch (Exception $e){
        header('Location: ../vues/general_gestionnaire');
    }
    
?>