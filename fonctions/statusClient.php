
<?php
//verifie que c'est bien un client
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] != 'client') {
    header('Location: ../vues/connexion.php');
}