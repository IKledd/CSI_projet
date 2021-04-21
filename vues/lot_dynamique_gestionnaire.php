<?php
require "../fonctions/bdd.php";
require "../fonctions/statusGestionnaire.php";
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
            $sql ="SELECT * FROM t_lot_lot WHERE lot_id= ".$lot;
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
                    <td>Nombre remises en vente</td>
                    <td><?php echo $result['lot_nombre_remise_vente'];?></td>
                </tr>
                <tr>
                    <td>Prix estimé</td>
                    <td><?php echo $result['lot_prix_estime'];?></td>
                </tr>
                <tr>
                    <td>Prix minimal</td>
                    <td><?php echo $result['lot_prix_minimal'];?></td>
                </tr>
                 <?php if($etat=='gagne'){    ?>
                    <tr>
                        <td>Prix d'achat final du produit</td>
                        <td><?php echo $result['lot_prix_achat'];?></td>
                    </tr>
                    <tr>
                        <td>Pseudo du gagnant du produit</td>
                        <td><?php echo $result['lot_gagnant'];?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


               

        </table>
        <table class="table table-striped">
            <thead>
                <tr>
                <th colspan="2">Proposition faites sur ce lot <?php echo $lot . " (" . $etat . ")"; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pseudo du client</td>
                    <td>Prix proposé</td>
                    <td>Nombre de modifications du client sur la proposition</td>
                    <td>Date de la proposition</td>
                </tr>
                <?php 
                //DISTINCT?
                    $sql2 ="select cli_pseudo,pro_prix_propose,pro_nombre_modification,pro_date_proposition from t_proposition_achat_pro where lot_id=".$lot;
                    $req2=$bdd->prepare($sql2);
                    $req2->execute();
                    while($row = $req2->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['cli_pseudo'];?></td>
                             <td><?php echo $row['pro_prix_propose'];?></td>
                            <td><?php echo $row['pro_nombre_modification'];?></td>
                            <td><?php echo $row['pro_date_proposition'];?></td>
                        </tr>
                        <?php
                    }
                ?>
              
                
            </tbody>
        </table>
           
        <input type="button" value="Retourner à votre page" onclick="javascript:location.href='./general_gestionnaire.php'" class="btn btn-outline-danger">


        
        </div>
    </body>
</html>

<?php } ?>