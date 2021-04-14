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
        <title>Login - Sign in</title>
        <link rel="stylesheet" type="text/css" href="../styles/login_form.css">
        <script type="text/javascript" src="../assets/login_form.js"></script>
        <meta charset="utf-8"/>
    </head>
    <body>
        <div id="form_box">
            <div>
                <!--action="index.html"-->
                <form action="homepage.php" onsubmit="verifForm(this)">
                    <p class="inscription">Vous êtes déjà inscrit?</p>
                    <div class="box">
                        <input class="boite" type="text" placeholder="Nom d'utilisateur" id="userIdLogin" onblur="verifPseudo(this, 'msgErreurIdLogin')"/>
                        <div class="msgErreur" id="msgErreurIdLogin"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="text" placeholder="Mot de passe" onblur="verifmdp(this, 'msgErreurMdpLogin')"/>
                        <div class="msgErreur" id="msgErreurMdpLogin"></div>
                    </div>
                    <input class="submit" type="submit"/>
                </form>
            </div>
            <div>
                <form onsubmit="return verifForm(this, 'msgErreurIdCreate', 'msgErreurMdpCreate')">
                    <p class="inscription">Creer un compte !</p>
                    <div class="box">
                        <input class="boite" type="text" placeholder="Nom d'utilisateur" name="userIdCreate" onblur="verifPseudo(this, 'msgErreurIdCreate')"/>
                        <div class="msgErreur" id="msgErreurIdCreate"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="text" placeholder="Mot de passe" name="userMdpCreate" onblur="verifmdp(this, 'msgErreurMdpCreate')"/>
                        <div class="msgErreur" id="msgErreurMdpCreate"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="text" placeholder="Confirmer le mot de passe" name="userMdpConfCreate" onblur="verifmdp(this, 'msgErreurMdpConfCreate')"/>
                        <div class="msgErreur" id="msgErreurMdpConfCreate"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="email" placeholder="Adresse e-mail" name="userMailCreate"/>
                        <div class="msgErreur" id="msgErreurMailCreate"></div>
                    </div>
                    <input class="submit" type="submit"/>
                </form>
            </div>
        </div>
        <div id="backgroundImage">
        </div>
    </body>
</html>