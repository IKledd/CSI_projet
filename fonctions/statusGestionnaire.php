//verifie que c'est bien un gestionnaire
<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] != 'gestionnaire') {
    header('Location: ../vues/login_form.php');
}