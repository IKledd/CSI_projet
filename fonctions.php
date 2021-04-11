<?php
     try {
    $db = new PDO("pgsql:host=localhost;dbname=projet_CSI","postgres","root",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    echo "Connected to db :D";
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>