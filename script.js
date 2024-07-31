
function calculerIMC() {
    debugger;
    let poids = parseFloat(document.getElementById('poids').value);

    let taille = parseFloat(document.getElementById('taille').value) / 100; 


    var imc = poids / (taille * taille);
    var resultat = document.getElementById('resultat');
    resultat.innerText = 'Votre IMC est : ' + imc.toFixed(2);
    if (imc < 18.5) {
        resultat.innerText += ' (Insuffisance pondérale)';
    } else if (imc >= 18.5 && imc < 24.9) {
        resultat.innerText += ' (Poids normal)';
    } else if (imc >= 25 && imc < 29.9) {
        resultat.innerText += ' (Surpoids)';
    } else {
        resultat.innerText += ' (Obésité)';
    }
}