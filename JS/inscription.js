
document.getElementById("cmdp").addEventListener("input",function() {
    var paragrapheErreur = document.getElementById("erreur1");

    if (this.value !=document.getElementById("Email").value) {
       paragrapheErreur.innerHTML = "Veuillez entrer le meme mot de passe";
    }else{
        paragrapheErreur.innerHTML = "" ;
    }
 })


document.getElementById("id2").addEventListener("submit",function(e){
 
 var username = document.getElementById("username");
 var userPname = document.getElementById("userPname");
 var Email = document.getElementById("Email");
 var mdp = document.getElementById("mdp");
 var cmdp = document.getElementById("cmdp");
 var erreur1 ;
 if (!cmdp.value.trim()) {
    erreur1 = "Veuillez confirmer le mot de passe avant de continuer"
 }
 if (!mdp.value.trim()) {
    erreur1 = "Veuillez entrer un mot de passe avant de continuer"
 }
 if (!Email.value.trim()) {
    erreur1 = "Veuillez entrer votre Email avant de continuer"
 }
 if (!userPname.value.trim()) {
    erreur1 = "Veuillez entrer votre prenom avant de continuer"
 }             
 if (!username.value.trim()) {
    erreur1 = "Veuillez entrer votre nom avant de continuer"
 }
 if (erreur1) {
    e.preventDefault();
    document.getElementById("erreur1").innerHTML = erreur1 ;
  
 }
})