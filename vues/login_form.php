<?php
    session_start();
    if (isset($_SESSION['user'])) {    
        $_SESSION['user']='';
        $_SESSION['id']='';
    }  

  require "../fonctions/bdd.php";
  $bdd = Bdd::getBdd();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login - Sign in</title>
        <!-- <link rel="stylesheet" type="text/css" href="../styles/login_form.css"> -->
        <script type="text/javascript" src="../assets/login_form.js"></script>
        <meta charset="utf-8"/>
    </head>
    <body>
        <?php
            if (isset($_GET['loginerror']) && $_GET['loginerror']) {
                echo "<span>Mot de passe incorrect</span></br>";
            }
        ?>
        <div id="form_box">
            <div id="div_connection">
                <!--action="index.html"-->
                <form action="../fonctions/traitementConnexion.php" method="post" onsubmit="verifForm(this)">
                    <p>Vous êtes déjà inscrit?</p>
                    <label for="login_conn">Login</label>
                    <input type="text" name="login_conn" id="login_conn" maxlength="256" required onblur="verifPseudo(this, 'msgErreurIdLogin')"/>
                    <div class="msgErreur" id="msgErreurIdLogin"></div>
                    
                    <label for="pwd_conn">Mot de passe</label>
                    <input type="password" name="pwd_conn" id="pwd_conn" required onblur="verifmdp(this, 'msgErreurMdpLogin')" maxlength="256" required/>
                    <div class="msgErreur" id="msgErreurMdpLogin"></div>
                
                    <input type="submit" value="Se connecter"/>
                </form>
            </div>   
            <div>
                <form method="post" action="../fonctions/traitementConnexion.php" onsubmit="return verifForm(this, 'msgErreurIdCreate', 'msgErreurMdpCreate')">
                    <p>Creer un compte !</p>

                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" required onblur="verifPseudo(this, 'msgErreurIdCreate')"/>
                    <div class="msgErreur" id="msgErreurIdCreate"></div>

                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" required/>
                    <div class="msgErreur" id="msgErreurIdCreate"></div>

                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" required/>
                    <div class="msgErreur" id="msgErreurIdCreate"></div>

                    <label for="pwd">Mot de passe</label>
                    <input type="password" name="pwd" id="pwd" required onblur="verifmdp(this, 'msgErreurMdpCreate')"/>
                    <div class="msgErreur" id="msgErreurMdpCreate"></div>

                    <label for="pwd_conf">Confirmez votre mot de passe</label>
                    <input type="password" name="pwd_conf" id="pwd_conf" required onblur="verifmdp(this, 'msgErreurMdpConfCreate')"/>
                    <div class="msgErreur" id="msgErreurMdpConfCreate"></div>

                    <input class="submit" type="submit" value="Creer un compte"/>
                </form>
            </div>
        </div>
        <div id="backgroundImage">
        </div> 
    </body>
</html>