<?php
    require "../fonctions/bdd.php";
    session_start();
    //Cas de la connexion
    if (isset($_POST['login_conn']) && isset($_POST['pwd_conn'])) {
        $login = $_POST['login_conn'];
        $pwd = $_POST['pwd_conn'];
        $bdd = Bdd::getBdd();
		
		// supprimer les proposition d'achat des lots avec date de fin > 24 heures
		$sql = 'call supprimer_propositions()';
		$req=$bdd->prepare($sql);
		$req->execute();

        // Analyser les propositions pour designer un gagnant pour les lot terminés
		$sql = 'call analyse_lots()';
		$req=$bdd->prepare($sql);
		$req->execute();

        $sql ="select connexion(?,?)";
        echo $sql;
        $req=$bdd->prepare($sql);
        $req->execute(array($login,$pwd));
        $result = $req->fetch(PDO::FETCH_ASSOC);
    
        if ($result['connexion']=='gestionnaire') {
            //variable de session à ajouter
            $_SESSION['user'] = 'gestionnaire';
            $_SESSION['pseudo'] = $login;
           echo 'gestionnaire';
            header('Location: ../vues/general_gestionnaire.php');
        }else if ($result['connexion']=='client') {
            //variable de session à ajouter
            $_SESSION['user'] = "client";
            $_SESSION['pseudo'] = $login;
            header('Location: ../vues/general_client.php');
        } else {
            echo 'non';
            //Cas mot de passe incorrect
            header('Location: ../vues/connexion.php?loginerror=true');
        }
    } else if(isset($_POST['login']) && isset($_POST['name']) && isset($_POST['first_name']) && isset($_POST['pwd']) && isset($_POST['pwd_conf'])){
        //Cas de l'inscription : on va toujours retouner sur la page connexion pour se connecter
        $login = $_POST['login'];
        $name = $_POST['name'];
        $first_name = $_POST['first_name'];
        $pwd = $_POST['pwd'];
        $pwd_conf = $_POST['pwd_conf'];

        if($pwd != $pwd_conf){
            echo 'erreur';
            header('Location: ../vues/connexion.php?loginerror=true');
            //faire erreur
        }else{
            $bdd = Bdd::getBdd();
            $sql ="call inscription('" . $login . "','" . $name . "','" . $first_name .  "','" . $pwd . "')";
            echo $sql;
            $req=$bdd->prepare($sql);
            $req->execute();
                  
            header('Location: ../vues/connexion.php');
        }       
    }else{
        header('Location: ../vues/connexion.php');
    }
