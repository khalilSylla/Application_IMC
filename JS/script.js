
function calculerIMC(event) {
    // event.preventDefault(); // Empêche le comportement par défaut du lien ou du bouton

    let poids = parseFloat(document.getElementById('Poids').value);
    let taille = parseFloat(document.getElementById('Taille').value) / 100;

    if (isNaN(poids) || isNaN(taille) || poids <= 0 || taille <= 0) {
        alert("Veuillez entrer des valeurs valides pour le poids et la taille.");
        return false;
    }

    let imc = poids / (taille * taille);
    let text = "";

    if (imc < 18.5) {
        text = "Insuffisance pondérale";
    } else if (imc >= 18.5 && imc < 24.9) {
        text = "Poids normal";
    } else if (imc >= 25 && imc < 29.9) {
        text = "Surpoids";
    } else {
        text = "Obésité";
    }

    // Redirection vers la page resultat.html avec les paramètres IMC et texte
    window.location.href = "resultat.html?imc=" + imc.toFixed(2) + "&text=" + encodeURIComponent(text);
}


