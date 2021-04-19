<?php
    require "../fonctions/bdd.php";
	require "../fonctions/statusClient.php";		  

?>
<!DOCTYPE html>
<html>
    <head>
		<title>client page</title>
		<link rel="stylesheet" type="text/css" href="../styles/client.css"> 
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

        <div>
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
				
      			<?php echo '<p >Solde : '. $com_solde .'<p>'; ?>

                <form method="post" action="../fonctions/traitementClient.php">
                            
                    <label for="solde" class="col-form-label">Ajouter au solde</label>
                    <input  type="number"  name="com_solde" id="solde" class="form-control" step="0.01" min="0"/>
                    <input type="hidden" name="com_compte" id="compte" class="col-form-label" value ="<?=$com_idcompte?>"/>
                    <input type="submit"  class="btn btn-outline-success "value="Modifier"/>

                </form>

        </div>
		<div>
		<br>
			<p >Lots à confirmer</p>
			<table class="table table-striped">
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
					

				<div class='jumbotron'>
					<legend>Sélectionner un lot en vente</legend><br>
					<form method="get" action="./lot_dynamique_client.php?>" id="form_on_sale">
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
					<form method="get" action="./lot_dynamique_client.php?>" id="form_sold">
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
            
       		<input type="button" value="Se déconnecter" onclick="javascript:location.href='./connexion.php'" class="btn btn-outline-danger">

    </body>
</html>