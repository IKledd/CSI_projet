<?php
require "../fonctions/bdd.php";
require "../fonctions/statusClient.php";
$bdd = Bdd::getBdd();
$lot = 1; 
/*if(isset($_GET['lot_on_sale'])){
   $lot = $_GET['lot_on_sale'];
}
else if(isset($_GET['lot_sold'])){
    $lot = $_GET['lot_sold'];
} */
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Informations de votre lot</title>
       <!-- <link rel="stylesheet" type="text/css" href=""> -->
        <meta charset="utf-8"/>
    </head>
    <body>
    
        <div id="showlot"> Lot numero 
        <?php 
            $sql ="SELECT * FROM v_affichage_client WHERE lot_id= ".$lot;
           // $sql ="SELECT afficher_lot_client(". $lot . ")";
           // echo $sql;
            $req=$bdd->prepare($sql);
            $req->execute();
            while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                echo $row['lot_id']." disponible jusque : " .$row['lot_date_fin_vente'];
                    }
        ?>
                
            </div>
        
        <div id="showinfo">
        <?php 
        $bdd = Bdd::getBdd();
        $sql = "SELECT lot_id FROM t_lot_lot WHERE ";
        echo $sql; 
        $req=$bdd->prepare($sql);
        $req->execute();
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) 
        {

            //Confirmer son achat(a)(page de lot dynamique)
            if( $row['gagnant'] = $row['pseudo'])
            echo "Vous êtes éligible à l'achat du lot" .$row['lot_id']."Vous devez confirmer son achat";
            //button pour changer la confirmation de faux à vrai
            echo "<button onclick=" pro_confirmation FROM t_proposition_achat_pro WHERE pro.lot_id=lot_id into">Click Here</button> "


              
        
         //Proposer un prix(page de lot dynamique)
         

         //Modifier proposition (à la hausse)(page de lot dynamique)

        

         //Voir le résultat de la vente(php)(page de lot dynamique)
        }
        ?> 
        </div>
    </body>
</html>