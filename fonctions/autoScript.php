<?php 
 require "../fonctions/bdd.php";

$bdd = Bdd::getBdd();
$prix = 0;
$sql ="select pro.lot_id from t_lot_lot lot,t_proposition_achat_pro pro,t_client_cli cli
where cli.cli_pseudo = pro.cli_pseudo and pro.lot_id = lot.lot_id
and cli.cli_pseudo =?";
$req=$bdd->prepare($sql);
$req->execute(array($_SESSION['pseudo']));
while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
$lot_id = $row['lot_id'] ;
}
  if (isset($_POST['lot_id'])) {
      $lot_id = $_POST['lot_id'];
$sql = 'call supprimer propositions(?)';
echo $sql;
$bdd = Bdd::getBdd();
$req=$bdd->prepare($sql);
$req->execute(array($lot_id));
}
?>
