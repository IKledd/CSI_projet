<?php
require "../fonctions/bdd.php";
require "../fonctions/statusClient.php";
$bdd = Bdd::getBdd();


if(!isset($_GET['lot_on_sale']) && !isset($_GET['lot_sold'])){
    echo 'Pas de lot sélectionné';
}else{
    if(isset($_GET['lot_on_sale'])){
        $lot = $_GET['lot_on_sale'];
        $etat='en vente';
    }
    else if(isset($_GET['lot_sold'])){
        $lot = $_GET['lot_sold'];
        $etat='gagne';
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
            //$sql ="SELECT * FROM v_affichage_client WHERE lot_id= ".$lot. " AND cli_pseudo='".$_SESSION['pseudo']."'";
            $sql ="SELECT * FROM t_proposition_achat_pro WHERE lot_id= ".$lot. " AND cli_pseudo='".$_SESSION['pseudo']."'";
            //$sql=SELECT DISTINCT pro_prix_propose,lot_date_debut_vente,lot_date_fin_vente,lot_prix_estime,pro_prix_propose,pro_nombre_modification,pro_date_proposition,lot_prix_achat FROM v_affichage_client WHERE lot_id= 1 AND cli_pseudo='ernesto1'
            $req=$bdd->prepare($sql);
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            //if(empty($result)){
                $sql_no_proposition ="SELECT * FROM t_lot_lot WHERE lot_id=".$lot;
                $req_no_proposition=$bdd->prepare($sql_no_proposition);
                $req_no_proposition->execute();
                $result_no_proposition = $req_no_proposition->fetch(PDO::FETCH_ASSOC);
            //}
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
                    <td><?php echo $result_no_proposition['lot_date_debut_vente'];?></td>
                </tr>
                <tr>
                    <td>Date de fin de vente</td>
                    <td><?php echo $result_no_proposition['lot_date_fin_vente'];?></td>
                </tr>
                <tr>
                    <td>Prix estimé</td>
                    <td><?php echo $result_no_proposition['lot_prix_estime'];?></td>
                </tr>
                 <?php
                    if(!empty($result)){
                ?>
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
                <?php } ?>

                <?php if($etat=='gagne'){    ?>

                    <tr>
                        <td>Prix d'achat final du produit</td>
                        <td><?php echo $result_no_proposition['lot_prix_achat'];?></td>
                    </tr>
                <?php } ?>
                <?php if($etat=='en vente'){ ?>
                    <tr>
                        <td>Proposer un prix</td>
                        <td>
                            <?php
                                echo '<form method="post" id="form_sold" action="../fonctions/traitementProposition.php?id=' . $lot . '" >'    
                                ?>     
                                <input  type="number"  name="prix" id="prix" class="form-control" step="0.01" min="0" onchange="document.getElementById('form_sold').submit();"/>
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
                    $sql2 ="select distinct q.prod_id,tprod_libelle,qprod_quantite,prod_marque,prod_datecreation from t_produit_prod as pr,t_quantite_qprod as q,t_type_produit_tprod as tp where pr.tprod_id=tp.tprod_id and tp.tprod_id=q.prod_id and lot_id=".$lot;
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

<?php } ?>