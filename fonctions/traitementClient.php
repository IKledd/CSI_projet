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
	
    if (isset($_GET['action']) && isset($_GET['lot_id'])) {
		$lot_id = $_POST['lot_id'];
		if ($_GET['action'] = 'refuserAchat') {
			 $sql = 'call refuser_achat(?)';
				$bdd = Bdd::getBdd();
				$req=$bdd->prepare($sql);
				$req->execute(array($lot_id));
			
		}
		if ($_GET['action'] = 'confirmerAchat') {
			 $sql = 'call achat_achat(?)';
				$bdd = Bdd::getBdd();
				$req=$bdd->prepare($sql);
				$req->execute(array($lot_id));
			
		}	
    
	}
	header('Location: ../vues/general_client');
	?>
	