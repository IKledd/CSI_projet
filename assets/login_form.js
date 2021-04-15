function surligne(champ, erreur, id) {
    "use strict";
    if (erreur) {
        champ.style.backgroundColor = "#FF0000";
        champ.style.opacity = "0.2";
    } else {
        document.getElementById(id).innerHTML = "";
        champ.style.backgroundColor = "";
        champ.style.opacity = "";
    }
}

function deleteText(id) {
    "use strict";
    document.getElementById(id).innerHTML = "";
}

function verifPseudo(champ, id) {
    "use strict";
    if (champ.value.length < 8) {
        surligne(champ, true, id);
        document.getElementById(id).innerHTML = "*Le nom d'utilisateur dois contenir au moins 8 caractères";
        return false;
    } else {
        surligne(champ, false, id);
        return true;
    }
}

function verifmdp(champ, id) {
    "use strict";
    if (champ.value.length < 8) {
        document.getElementById(id).innerHTML = "*Le mot de passe dois contenir au moins 8 caractères";
        surligne(champ, true, id);
        return false;
    } else {
        surligne(champ, false, id);
        return true;
    }
}

function verifForm(f, id, mdp) {
    "use strict";
    console.log("A");
    var pseudoOk = verifPseudo(f, id),
        mdpOk = verifmdp(f, mdp);
    console.log(pseudoOk + mdpOk);
    if (pseudoOk && mdpOk) {
        return true;
    } else {
        window.alert("Veuillez remplir correctement tous les champs");
        return false;
    }
}