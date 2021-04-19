<?php
require "../fonctions/bdd.php";
require "../fonctions/statusClient.php";
$bdd = Bdd::getBdd();
$lot = 1; 
if(isset($_GET['lot_on_sale'])){
   $lot = $_GET['lot_on_sale'];
   $etat='en vente';
}
else if(isset($_GET['lot_sold'])){
    $lot = $_GET['lot_sold'];
    $etat='vendu';
} 
else{
    //header('Location: ./general_client.php');
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Lot numéro <?php echo $lot; ?></title>
       <!-- <link rel="stylesheet" type="text/css" href=""> -->
        <meta charset="utf-8"/>
    </head>
    <body>
    
        <p id="titlz">Lot numéro <?php echo $lot; ?></p>
        <?php 
            $sql ="SELECT * FROM v_affichage_client WHERE lot_id= ".$lot;
           // $sql ="SELECT afficher_lot_client(". $lot . ")";
           // echo $sql;
            $req=$bdd->prepare($sql);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
           echo($result['lot_date_debut_vente']);

        ?>
        <table>
            <thead>
                <tr>
                <th colspan="2">Informations sur le lot numéro <?php echo $lot . " (" . $etat . ")"; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Date de début de vente</td>
                    <td><?php echo $result['lot_date_debut_vente'];?></td>
                </tr>
                <tr>
                    <td>Date de fin de vente</td>
                    <td><?php echo $result['lot_date_fin_vente'];?></td>
                </tr>
                <tr>
                    <td>Prix estimé</td>
                    <td><?php echo $result['lot_prix_estime'];?></td>
                </tr>
            </tbody>
        </table>
           <!--  </div>
        
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
            /*echo "<button onclick=" pro_confirmation FROM t_proposition_achat_pro WHERE pro.lot_id=lot_id into">Click Here</button> ";*/


              
        
         //Proposer un prix(page de lot dynamique)
         

         //Modifier proposition (à la hausse)(page de lot dynamique)

        

         //Voir le résultat de la vente(php)(page de lot dynamique)
        }
        ?>  -->
        </div>
    </body>
</html>