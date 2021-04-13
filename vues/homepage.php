<?php
     try {
    $db = new PDO("pgsql:host=localhost;dbname=projet_CSI","postgres","root",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    echo "Connected to db :D";
} catch (PDOException $e) {
    echo $e->getMessage();
}
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
            <div class="menuButton"> Vendre
                
            </div>
            <div class="menuButton"> Rechercher
                
            </div>
            <div class="menuButton"> Autre
                
            </div>
        </div>
        
        <div id="actions">
            
        </div>
    </body>
</html>