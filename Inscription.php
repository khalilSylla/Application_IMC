<!DOCTYPE html>
<html lang="fr
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page_d'inscription_FitTrack</title>
<link rel="stylesheet" href="CSS/Design_Inscription_IMC.css">
</head>
<body>
     <div id="id1"> <img class="cl12" src="img/LOGO.png" alt="Logo" height="50px"><h3><b>FitTrack</b></h3>
        <header><a class="home" href="Application_IMC/home.html">Accueil</a>
         <a class="con" href="Application_IMC/connexion.html">Se Connecter</a>
            <a class="about" href="#">A Propos</a></header></div>
    <h1><b>&nbsp;&nbsp;Inscrivez Vous</b></h1>
    <h2>&nbsp;&nbsp;&nbsp;Connaitre son IMC</h2>
    <P>Découvrez FitTrack , notre application innovante <br> qui calcule votre IMC 
        , suit votre sante et vous <br> aide à atteindre vos objectifs
         de bien-être en <br> toute simplicité</P>
         <form action="Traitement_inscription.php" method="post" id="id2" >

            <label for="username" class="username">Nom</label>
            <input type="text" name="Nom" id="username" class="cl1" placeholder="Entrer votre Nom" required >
            
            <label for="userPname" class="userPname">Prenom</label>
            <input type="text" name="Prenom" id="userPname" class="cl2" placeholder="Entrer votre Prenom" required><br>
            
            <label for="Email" class="Email">Email</label>
            <input type="email" name="Email" id="Email" class="cl3" placeholder="Entrer votre adresse Email" required><br> 
            
            <label for="text" class="mdp" >Mot de passe</label>
            <input type="password" name="Mot_de_passe" id="mdp" class="cl4" placeholder="Entrer votre un mot de passe" required> <br>
            
            <label for="text" class="cmdp">Confirmer le mot de passe</label>
            <input type="password" name="Confirmer_mot_de_passe" id="cmdp" class="cl5" placeholder="Confirmer votre mot de passe" required> <br>
            
            <div id="selection_genre">
               <label for="genre" class="genre">Genre</label><br>
               <select name="Genre" id="genre" class="cl5" required>
                     <option value="">Sélectionner votre genre</option>
                     <option value="1">Homme</option>
                     <option value="2">Femme</option>
               </select><br>
            </div>
            
            <button type="submit" id="inscrire" name="envoyer" class="cl6">S'inscrire </button>
            
            <button onclick="window.location.href='home.html';" id="Annuler" class="cl7">Annuler</button>           
         
         </form>
         <div class="cl8"><img class="cl10" src="img/TEL.png" height="40px" alt="tel">123-456-789 </div>
         <div class="cl9"><img class="cl11" src="img/ADRESSE.png" height="40px" alt="adresse">FitTrack@gmail.com</div>
         <Span style="color: red;" id="erreur1"></Span><br>
         <Span style="color: red;" id="erreur2"></Span>
         <script src="JS/inscription.js"></script>

</body>
</html>