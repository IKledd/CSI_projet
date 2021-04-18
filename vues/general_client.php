<?php
    require "../fonctions/bdd.php";
	require "../fonctions/statusClient.php";		  

?>
<!DOCTYPE html>
<html>
    <head>
		<title>client page</title>
		<link rel="stylesheet" type="text/css" href="../styles/client.css">
		
		
   
        <meta charset="utf-8"/>
    </head>
    <body>
	<div class="row">

        <div >
		<div>
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
                <h1>Modifier solde</h1>
                <form method="post" action="../fonctions/traitementClient.php">
                            
                    <label for="solde">Ajouter au solde</label>
                    <input  type="text" name="com_solde" id="solde"/>
                    <input type="hidden" name="com_compte" id="compte" value ="<?=$com_idcompte?>"/>
                    <input type="submit" value="Modifier"/>

                </form>
				Solde : <?php echo $com_solde ?>
        </div>
		<div ">
		<br>
			<h1>Lots à confirmer</h1>
			<table id="customers">
				<tr>
					<td>Lot N°</td>
					<td>Prix d'achat</td>
					<td>Actions</td>
				</tr>
			<?php
				$sql ="select lot_id , lot_etat, lot_prix_achat 
					from t_lot_lot 
					where lot_gagnant = ?
					and lot_etat = 'a confirmer'";
				$req=$bdd->prepare($sql);
				$req->execute(array($_SESSION['pseudo']));
				while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
					echo '<tr>';
					echo '	<td>'. $row['lot_id'] . '</td>';
					echo '	<td>'. $row['lot_prix_achat'] . '</td>';
					echo '	<td><a href="../fonctions/traitementClient.php?action=confirmerAchat&lot_id=' . $row['lot_id'] . '" >Confirmer</a> &nbsp;'; 
					echo '      <a href="../fonctions/traitementClient.php?action=refuserAchat&lot_id=' . $row['lot_id'] . '" >Refuser</a>';
					echo '  </td>';
					echo '</tr>';
				}
			?>
			</table>

        </div>
		</div>
	</div>
		<div id="actions">
					<h1>Consulter les lots</h1>

				<div class='jumbotron'>
					<legend>Sélectionner un lot en vente</legend><br>
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
					<legend>Sélectionner un lot vendu</legend><br>
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