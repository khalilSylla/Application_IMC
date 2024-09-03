document.getElementById("id2").addEventListener("submit", function(event) {
   // Récupération des valeurs des champs
   var motDePasse = document.getElementById("mdp").value;
   var confirmerMotDePasse = document.getElementById("cmdp").value;
   
   // Références aux éléments où les messages d'erreur seront affichés
   var erreur1 = document.getElementById("erreur1");
   var erreur2 = document.getElementById("erreur2");
   
   // Initialisation des messages d'erreur
   erreur1.textContent = "";
   erreur2.textContent = "";
   
   var valid = true;

   // Vérification du nombre minimum de caractères
   if (motDePasse.length < 8) {
       // Affiche le message d'erreur pour le mot de passe trop court
       erreur1.textContent = "Le mot de passe doit contenir au minimum huit caractères";
       valid = false; // Le formulaire n'est pas valide
   }

   // Vérification si les mots de passe correspondent
   if (motDePasse !== confirmerMotDePasse) {
       // Affiche le message d'erreur si les mots de passe ne correspondent pas
       erreur2.textContent = "Veuillez entrer le mème mot de passe lors de la confirmation.";
       valid = false; // Le formulaire n'est pas valide
   }

   // Si le formulaire n'est pas valide, empêcher l'envoi
   if (!valid) {
       event.preventDefault(); // Empêche l'envoi du formulaire
   }
});