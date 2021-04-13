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
                <form action="homepage.php" onsubmit="return verifForm(this)">
                    <p class="inscription">Vous êtes déjà inscrit?</p>
                    <div class="box">
                        <input class="boite" type="text" id="userIdLogin" onblur="verifPseudo(this, 'msgErreurIdLogin')" onfocus="this.value=''" value=" Nom d'utilisateur"/>
                        <div class="msgErreur" id="msgErreurIdLogin"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="text" onfocus="this.value=''" onblur="verifmdp(this, 'msgErreurMdpLogin')" value=" Mot de passe"/>
                        <div class="msgErreur" id="msgErreurMdpLogin"></div>
                    </div>
                    <input class="submit" type="submit" value="Connexion" />
                </form>
            </div>
            <div>
                <form id="connexion" onsubmit="return verifStyleMus(this)">
                    <p class="inscription">Creer un compte !</p>
                    <div class="box">
                        <input class="boite" type="text" onblur="verifPseudo(this, 'msgErreurIdCreate')"  onfocus="this.value=''" value=" Nom d'utilisateur"/>
                        <div class="msgErreur" id="msgErreurIdCreate"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="text" onblur="verifmdp(this, 'msgErreurMdpCreate')" onfocus="this.value=''" value=" Mot de passe"/>
                        <div class="msgErreur" id="msgErreurMdpCreate"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="text" onblur="verifmdp(this, 'msgErreurMdpConfCreate')"  onfocus="this.value=''" value=" Confirmer le mot de passe"/>
                        <div class="msgErreur" id="msgErreurMdpConfCreate"></div>
                    </div>
                    <div class="box">
                        <input class="boite" type="email" onfocus="this.value=''" value=" Adresse e-mail"/>
                        <div class="msgErreur" id="msgErreurMailCreate"></div>
                    </div>
                    <input class="submit" type="submit" value=" Creer un compte" />
                </form>
            </div>
        </div>
        <div id="backgroundImage">
        </div>
    </body>
</html>