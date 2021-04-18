<?php
    require "../fonctions/bdd.php";

session_start();
    //Cas de la connexion

    if (isset($_POST['com_compte']) && isset($_POST['com_solde'])) {
      $com_idcompte = $_POST['com_compte'];
      $com_solde =   $_POST['com_solde'];
	  echo '$com_idcompte : '.  $com_idcompte;
	  echo '$com_solde : '.  $com_solde;
	  
	  $sql = 'call modifier_solde(?,?)';
	  echo $sql;
	  $bdd = Bdd::getBdd();
	  $req=$bdd->prepare($sql);
         $req->execute(array($com_solde,$com_idcompte));
	}
	//header('Location: ../vues/general_client');
	?>
	