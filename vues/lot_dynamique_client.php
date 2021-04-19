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
    $etat='gagne';
} 
else{
    //header('Location: ./general_client.php');
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Lot numéro <?php echo $lot; ?></title>
        <link rel="icon" type="image/png" href="./../images/favicon.png" />
       <!-- <link rel="stylesheet" type="text/css" href=""> -->
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

        <?php 
            $sql ="SELECT * FROM v_affichage_client WHERE lot_id= ".$lot;
            $req=$bdd->prepare($sql);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
        ?>
        <table class="table table-striped">
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
                <tr>
                    <td>Prix proposé</td>
                    <td><?php echo $result['pro_prix_propose'];?></td>
                </tr>
                <tr>
                    <td>Votre nombre de modification de la proposition</td>
                    <td><?php echo $result['pro_nombre_modification'];?></td>
                </tr>
                <tr>
                    <td>Date de votre dernière porposition</td>
                    <td><?php echo $result['pro_date_proposition'];?></td>
                </tr>

                <?php if($etat=='gagne'){    ?>

                    <tr>
                        <td>Prix d'achat final du produit</td>
                        <td><?php echo $result['lot_prix_achat'];?></td>
                    </tr>
                <?php } ?>
                <?php if($etat=='en vente'){ ?>
                    <tr>
                        <td>Proposer un prix</td>
                        <td>
                            <form method="post" action="../fonctions/traitementProposition.php" onchange="document.getElementById('form_sold').submit();">          
                                <input  type="number"  name="com_solde" id="solde" class="form-control" step="0.01" min="0"/>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <table class="table table-striped">
            <thead>
                <tr>
                <th colspan="2">Composition du lot numéro <?php echo $lot . " (" . $etat . ")"; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Libellé</td>
                    <td>Quantité</td>
                    <td>Marque</td>
                    <td>Date de sortie</td>
                </tr>
                <?php 
                    $sql2 ="select distinct prod_id,tprod_libelle,qprod_quantite,prod_marque,prod_datecreation from v_affichage_client where lot_id=".$lot;
                    $req2=$bdd->prepare($sql2);
                    $req2->execute();
                    while($row = $req2->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['tprod_libelle'];?></td>
                             <td><?php echo $row['qprod_quantite'];?></td>
                            <td><?php echo $row['prod_marque'];?></td>
                            <td><?php echo $row['prod_datecreation'];?></td>
                        </tr>
                        <?php
                    }
                ?>
              
                
            </tbody>
        </table>
           
        <input type="button" value="Retourner à votre page" onclick="javascript:location.href='./general_client.php'" class="btn btn-outline-danger">

              
        
         <!-- //Proposer un prix(page de lot dynamique)
         

         //Modifier proposition (à la hausse)(page de lot dynamique)
 -->
        

        
        </div>
    </body>
</html>