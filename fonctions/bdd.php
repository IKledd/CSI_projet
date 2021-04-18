<?php
class Bdd
    {
        private static $bddConnection; //On stock la connexion à la bdd
        private static $bddObject = null; //On stock l'objet Bdd afin de créer une seule connexion à la bdd

        private function __construct()
        {
            try {
                Bdd::$bddConnection = new PDO("pgsql:host=localhost;dbname=projet_csi_groupe_11","postgres","root",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $ex) {
                die('Impossible de joindre la base de données : '. $ex->getMessage() );
            }
        }
        // Obtention de la connexion à la base de données
        public static function getBdd()
        {
            if (Bdd::$bddObject == null) { // Si aucune connexion existe alors on en créé une sinon on retourne celle existante
                Bdd::$bddObject = new Bdd();
            }
            return Bdd::$bddConnection;
        }
    }
?>