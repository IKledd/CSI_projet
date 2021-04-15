//verifie que c'est bien un gestionnaire
<?php
session_start();
if (!isset($_SESSION["connected"]) || $_SESSION["connected"] != 'gestionnaire') {
    header('Location: ../vues/login_form.php');
}