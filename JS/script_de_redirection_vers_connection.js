// Vérifier si le paramètre 'inscription' est présent dans l'URL
const urlParams = new URLSearchParams(window.location.search);
const inscriptionStatus = urlParams.get('inscription');

if (inscriptionStatus === 'ok') {
    // Afficher une alerte si l'inscription a réussi
    alert('Inscription réussie ! Vous pouvez maintenant vous connecter.');
}