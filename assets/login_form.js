function surligne(champ, erreur) {
    "use strict";
    if (erreur) {
        champ.style.backgroundColor = "#FF0000";
        champ.style.opacity = "0.2";
    } else {
        document.getElementById("msgErreur").innerHTML = "";
        champ.style.backgroundColor = "";
    }
}

function deleteText(id){
    "use strict"
    document.getElementById(id).innerHTML = "A";
}

function verifPseudo(champ, id) {
    "use strict";
    if (champ.value.length < 4) {
        surligne(champ, true);
        document.getElementById(id).innerHTML = "*Le nom d'utilisateur dois contenir au moins 4 caractères";
        return false;
    } else {
        surligne(champ, false);
        return true;
    }
}

function verifmdp(champ, id) {
    "use strict";
    if (champ.value.length < 8) {
        document.getElementById(id).innerHTML = "*Le mot de passe dois contenir au moins 8 caractères";
        surligne(champ, true);
        return false;
    } else {
        surligne(champ, false);
        return true;
    }
}

function verifForm(f) {
    "use strict";
    var pseudoOk = verifPseudo(f.pseudo),
        mdpOk = verifmdp(f.mdp);
   
    if (pseudoOk && mdpOk) {
        return true;
    } else {
        alert("Veuillez remplir correctement tous les champs");
        return false;
    }
}