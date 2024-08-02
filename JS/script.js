
function calculerIMC() {
    let poids = parseFloat(document.getElementById('Poids').value);

    let taille = parseFloat(document.getElementById('Taille').value) / 100; 


    var imc = poids / (taille * taille);
    // var resultat = document.getElementById('resultat');
    var text="";
    // resultat.innerText = 'Votre IMC est : ' + imc.toFixed(2);
    if (imc < 18.5) {
        // resultat.innerText += ' (Insuffisance pondérale)';
        text="Insuffisance ponderale";
    } else if (imc >= 18.5 && imc < 24.9) {
        // resultat.innerText += ' (Poids normal)';
        text = "Poids normal";
    } else if (imc >= 25 && imc < 29.9) {
        // resultat.innerText += ' (Surpoids)';
        text="Surpoids";
    } else {
        // resultat.innerText += ' (Obésité)';
         text = "obesite";
    }
    window.location.href="resultat.html?imc="+imc+"&text="+text;
}