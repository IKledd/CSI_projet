<?php
    require "../fonctions/bdd.php";
	require "../fonctions/statusClient.php";		  

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home page</title>
        <link rel="stylesheet" type="text/css" href="../styles/homePage.css">
        <meta charset="utf-8"/>
    </head>
    <body>
        <div id="menuBar">
		<div class="menuButton">
		                <?php
                            $bdd = Bdd::getBdd();
							$com_idcompte = 0;
							$com_solde = 0;
                            $sql ="select com.com_idcompte,com.com_solde from t_compte_courant_com com ,t_client_cli cli
							where cli.com_idcompte = com.com_idcompte and cli.cli_pseudo =?";
                            $req=$bdd->prepare($sql);
                            $req->execute(array($_SESSION['pseudo']));
                            while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
								$com_idcompte = $row['com_idcompte'] ;
								$com_solde = $row['com_solde'] ;
                                /*echo "<option value='" . $row['com_idcompte'] . "'>";
                                echo "n° ".$row['com_idcompte']." : " .$row['com_solde'];
                                echo "</option>";   */
                            }
                        ?>
                <legend><center>Modifier solde</center></legend>
                <form method="post" action="../fonctions/traitementClient.php">
                            
                    <label class="label_form" for="solde">Ajouter au solde</label>
                    <input class="form_input" type="text" name="com_solde" id="solde"/>
                    <input type="hidden" name="com_compte" id="compte" value ="<?=$com_idcompte?>"/>
                    <input type="submit" value="Modifier"/>

                </form>
				Solde : <?php echo $com_solde ?>
            </div>
		</div>

		<div id="actions">
				<div class='jumbotron'>
					<legend><center>Sélectionner un lot en vente</center></legend>
					<form method="get" action="./lot_dynamique_gestionnaire.php?>" id="form_on_sale">
						<select type="text" class="form-control" id="lot_on_sale" name="lot_on_sale" required onchange="document.getElementById('form_on_sale').submit();">
							<?php
								$bdd = Bdd::getBdd();
								$sql ="SELECT lot_id,lot_date_fin_vente FROM t_lot_lot where lot_etat='en vente'";
								$req=$bdd->prepare($sql);
								$req->execute();
								echo "<option selected disabled>Lots en vente</option>";
								while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
									echo "<option value='" . $row['lot_id'] . "'>";
									echo $row['lot_id']." disponible jusque : " .$row['lot_date_fin_vente'];
									echo "</option>";   
								}
							?>
						</select>
					</form>
				</div>

				<div class='jumbotron'>
					<legend><center>Sélectionner un lot vendu</center></legend>
					<form method="get" action="./lot_dynamique_gestionnaire.php?>" id="form_sold">
						<select type="text" class="form-control" id="lot_sold" name="lot_sold" required onchange="document.getElementById('form_sold').submit();">
						<?php
							$bdd = Bdd::getBdd();
							$sql ="SELECT lot_id,lot_prix_achat,lot_gagnant FROM t_lot_lot where lot_etat='gagne'";
							echo $sql;
							$req=$bdd->prepare($sql);
							$req->execute();
							echo "<option selected disabled>Lots vendus</option>";
							while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
								echo "<option value='" . $row['lot_id'] . "'>";
								echo $row['lot_id']." vendu pour : " .$row['lot_prix_achat'] . " à " . $row['lot_gagnant'];
								echo "</option>";   
							}
						?>
						</select>
					</form>
				</div>
        </div>
            
       
    </body>
</html>