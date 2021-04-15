<?php
    require "./bdd.php";
    session_start();
    //Cas de la connexion
    if (isset($_POST['login_conn']) && isset($_POST['pwd_conn'])) {
        $login = $_POST['login_conn'];
        $pwd = $_POST['pwd_conn'];
        $bdd = Bdd::getBdd();

        $sql ="select connexion('" . $login . "','" . $pwd . "')";
        echo $sql;
        $req=$bdd->prepare($sql);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
    
        if ($result['connexion']=='gestionnaire') {
            //variable de session à ajouter
            $_SESSION['user'] = 'gestionnaire';
            $_SESSION['id'] = $login;
           echo 'gestionnaire';
            header('Location: ../vues/homepage.php');
        }else if ($result['connexion']=='client') {
            //variable de session à ajouter
            $_SESSION['user'] = "client";
            $_SESSION['id'] = $login;
           echo $_SESSION['user'] . $_SESSION['id'];
            header('Location: ../vues/compose_lot.php');
            //header('Location: ../vues/login_form.php');
        } else {
            echo 'non';
            //Cas mot de passe incorrect
            header('Location: ../vues/login_form.php?loginerror=true');
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
            header('Location: ../vues/login_form.php?loginerror=true');
            //faire erreur
        }else{
            $bdd = Bdd::getBdd();
            $sql ="call inscription('" . $login . "','" . $name . "','" . $first_name .  "','" . $pwd . "')";
            echo $sql;
            $req=$bdd->prepare($sql);
            $req->execute();
                  
            header('Location: ../vues/login_form.php');
        }       
    }else{
        header('Location: ../vues/login_form.php');
    }
