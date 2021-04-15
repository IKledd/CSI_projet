<?php
     try {
    $db = new PDO("pgsql:host=localhost;dbname=projet_csi_groupe_11","postgres","ernesto",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    echo "Connected to db :D";
} catch (PDOException $e) {
	echo "test";
    echo $e->getMessage();
}
?>