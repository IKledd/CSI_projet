<?php
    require "../fonctions/bdd.php";
    session_start();

    var_dump($_POST);

    if(isset($_POST['prix'])){
        //Cas de l'inscription : on va toujours retouner sur la page connexion pour se connecter
        $prix = $_POST['prix'];

        $bdd = Bdd::getBdd();
        $sql ="call proposer_prix('" . $_GET['id'] . "','" . $prix . "','" . $_SESSION['pseudo'] . "')";
        echo $sql;
        $req=$bdd->prepare($sql);
        $req->execute();
                  
        header('Location: ../vues/general_client.php');
             
    }else{
    	echo 'non';
        //header('Location: ../vues/connexion.php');
    }
?>
