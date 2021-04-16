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
            echo $sql;
            $req=$bdd->prepare($sql);
            $req->execute();
            while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['lot_id'] . "'>";
                echo $row['lot_id']." disponible jusque : " .$row['lot_date_fin_vente'];
                echo "</option>";   
                    }
        ?>
                
            </div>
        
        <div id="showinfo">
        <?php 
        $bdd = Bdd::getBdd();
        $sql = "SELECT lot_id FROM t_lot_lot WHERE ";
        echo $sql;
        ?>
        </div>
    </body>
</html>