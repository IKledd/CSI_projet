<?php
     try {
        $db = new PDO("pgsql:host=localhost;dbname=projet_CSI","postgres","A157z874D",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        echo "Connected to db :D";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    session_start();
    if (isset($_SESSION['user']) || isset($_SESSION['pseudo']) ) {    
        $_SESSION['user']='';
        $_SESSION['id']='';
    }  

  require "../fonctions/bdd.php"; //bdd.php dbname mdp edited to work
  $bdd = Bdd::getBdd();


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
        $bdd = Bdd::getBdd();
        $sql = "SELECT lot_id FROM t_lot_lot";
        $result = $db->query($sql);
        echo $result;
        $req=$bdd->prepare($sql);
        $req->execute();
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