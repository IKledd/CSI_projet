<?php
    require "../fonctions/bdd.php";
	$bdd = Bdd::getBdd();
	session_start();
    //Cas de la connexion

    if (isset($_POST['com_compte']) && isset($_POST['com_solde'])) {
      $com_idcompte = $_POST['com_compte'];
      $com_solde =   $_POST['com_solde'];
	  echo '$com_idcompte : '.  $com_idcompte;
	  echo '$com_solde : '.  $com_solde;
	  
	  $sql = 'call modifier_solde(?,?)';
	  echo $sql;
	  $req=$bdd->prepare($sql);
         $req->execute(array($com_solde,$com_idcompte));
	}
	
    if (isset($_GET['action']) && isset($_GET['lot_id'])) {
		$lot_id = $_GET['lot_id'];
		
		if ($_GET['action'] == 'confirmerAchat') {
			 $sql = 'call confirmer_achat(?)';
				$req=$bdd->prepare($sql);
				$req->execute(array($lot_id));
			
		}	
		if ($_GET['action'] == 'refuserAchat') {
			 $sql = 'call refuser_achat(?)';
				$req=$bdd->prepare($sql);
				$req->execute(array($lot_id));
			
		}
    
	}
	header('Location: ../vues/general_client');
	?>