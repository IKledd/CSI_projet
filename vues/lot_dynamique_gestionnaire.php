<?php echo "lot dynamique gestionnaire"; 
	if(isset($_GET['lot_on_sale'])){
		echo "en vente : " . $_GET['lot_on_sale'];
	}
	if(isset($_GET['lot_sold'])){
		echo "vendu : " . $_GET['lot_sold'];
	}
?>