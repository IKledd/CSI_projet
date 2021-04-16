<?php
    session_start();
    if (isset($_SESSION['user']) || isset($_SESSION['pseudo']) ) {    
        $_SESSION['user']='';
        $_SESSION['id']='';
    }  

  require "../fonctions/bdd.php";
  $bdd = Bdd::getBdd();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Connexion-Inscription</title>
        <link rel="icon" type="image/png" href="./../images/favicon.png" />
        <link rel="stylesheet" type="text/css" href="../styles/connexion.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
        <script type="text/javascript" src="../assets/connexion.js"></script>
        <meta charset="utf-8"/>
    </head>
    <body>
        <?php
            if (isset($_GET['loginerror']) && $_GET['loginerror']) {
                echo "<span>Mot de passe incorrect</span></br>";
            }
        ?>
        <div id="contain">
            <div id="div_connection" class="jumbotron">
                <legend>
                <center>Vous êtes déjà inscrit ?</center>
                </legend>
                <form action="../fonctions/traitementConnexion.php" method="post" onsubmit="verifForm(this)">
                    <div class="form-group">
                        <label for="login_conn" class="col-form-label">Login</label>
                        <input type="text" class="form-control" name="login_conn" id="login_conn" maxlength="256" required onblur="verifPseudo(this, 'msgErreurIdLogin')"/>
                        <div class="msgErreur" class="form-control" id="msgErreurIdLogin"></div>
                        
                        <label for="pwd_conn" class="col-form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="pwd_conn" id="pwd_conn" required onblur="verifmdp(this, 'msgErreurMdpLogin')" maxlength="256" required/>
                        <div class="msgErreur" class="form-control" id="msgErreurMdpLogin"></div>
                    
                        <input type="submit" value="Se connecter" class="btn btn-outline-success"/>
                    </div>
                </form>
            </div>   
            
                <div id="div_submit" class="jumbotron">
                    <legend>
                        <center>Créer un compte !</center>
                    </legend>
                    <form method="post" action="../fonctions/traitementConnexion.php" onsubmit="return verifForm(this, 'msgErreurIdCreate', 'msgErreurMdpCreate')">
                        <div class="form-group">
                            <label for="login" class="col-form-label">Login</label>
                            <input type="text" class="form-control" name="login" id="login" required onblur="verifPseudo(this, 'msgErreurIdCreate')"/>
                            <div class="msgErreur" class="form-control" id="msgErreurIdCreate"></div>

                            <label for="name" class="col-form-label">Nom</label>
                            <input type="text" class="form-control" name="name" id="name" required/>
                            <div class="msgErreur" class="form-control" id="msgErreurIdCreate"></div>

                            <label for="first_name" class="col-form-label">Prénom</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" required/>
                            <div class="msgErreur" class="form-control" id="msgErreurIdCreate"></div>

                            <label for="pwd" class="col-form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="pwd" id="pwd" required onblur="verifmdp(this, 'msgErreurMdpCreate')"/>
                            <div class="msgErreur" class="form-control" id="msgErreurMdpCreate"></div>

                            <label for="pwd_conf" class="col-form-label">Confirmez votre mot de passe</label>
                            <input type="password" class="form-control" name="pwd_conf" id="pwd_conf" required onblur="verifmdp(this, 'msgErreurMdpConfCreate')"/>
                            <div class="msgErreur" class="form-control" id="msgErreurMdpConfCreate"></div>

                            <input type="submit" value="Créer un compte" class="btn btn-outline-success"/>
                        </div>
                    </form>
                </div>
            
        </div> 
    </body>
</html>