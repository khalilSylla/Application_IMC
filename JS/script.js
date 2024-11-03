function calculerIMC(event) {
    debugger;
    // event.preventDefaut();
    let poids = parseFloat(document.getElementById('Poids').value);
    let taille = parseFloat(document.getElementById('Taille').value)/100;
    let erreur = "";
    
    if (isNaN(poids) || isNaN(taille) || poids<=0 || taille<=0){
        erreur ="Veuillez entrer des valeurs valides pour le poids et la taille.";
    }
    if (erreur){
        document.getElementById("erreur").innerHTML = erreur;
        return false;
    }
    let imc = poids / (taille * taille);
    let text = "";
    // let className ="";
    if (imc < 18.5) {
        text = "Insuffisance pondérale";
        // className = "insuffisance";
    } else if (imc >= 18.5 && imc < 24.9) {
        text = "Poids normal";
        // className = "normal";
    } else if (imc >= 25 && imc < 29.9) {
        text = "Surpoids";
        className = "surpoids";
    } else {
        text = "Obésité";
    }
    // Redirection vers la page resultat.html avec les paramètres IMC et texte
    window.location.href = "resultat.html?imc=" + imc.toFixed(2) + "&text=" + encodeURIComponent(text);

    

}

