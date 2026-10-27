<?php
//Vérifie si une session existe déja
if (session_status() === PHP_SESSION_NONE) {
    //Crée une session
    session_start();
}

require_once("fonction.php");

//Récupere le mots de passe et l'email du formulaire
$email = $_POST['email'] ?? '';
$mdp = $_POST['mdp'] ?? '';

$bdd = connexion();
if ($bdd) {
    //Sécurise l'email
    $email_sec = mysqli_real_escape_string($bdd, $email);
    //Requete pour recuperer login et motsdepasse du client
    $rq = "SELECT login, motDePasse FROM client WHERE email = '$email_sec'";
    //Execute la requete
    $requete = mysqli_query($bdd, $rq);
    //Vérifie si la requete a marcher
    if ($requete && mysqli_num_rows($requete) > 0) {
        $ligne = mysqli_fetch_assoc($requete);

        //Vérifie mots de passe 
        if ($mdp === $ligne['motDePasse'] || password_verify($mdp, $ligne['motDePasse'])) {
            //Enregistre le login de l'utilisateur
            $_SESSION['utilisateur'] = $ligne['login'];
            header("Location: index.php");
            exit();
        }
    }
}

//Si sa marche pas on re affiche le formulaire
$erreur = "Email ou mot de passe incorrect !";
include("connexion.php");
?>