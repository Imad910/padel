<?php 
//Démarre la session PHP
session_start(); 
?>
<html>
    <head>
        <title>Traitement Inscription</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="auth-wrapper">
            <div class="articlee" style="text-align: center;">
<?php
//Inclut le fichier de connexion
include("fonction.php");

//Récupération des données du formulaire
$user = $_POST['user'];
$mdp = $_POST['mdp'];
$email = $_POST['email'];

//Connexion à la bdd
$bdd = connexion();

if ($bdd) {
    //On vérifie si le login OU l'email existe
    $verif = "SELECT * FROM client WHERE login = '$user' OR email = '$email'";

    //execute la requete sql
    $resultatVerif = mysqli_query($bdd, $verif);

    if (mysqli_num_rows($resultatVerif) > 0) {
        // Si supérieur à 0, le pseudo ou l'email est déjà pris
        echo "<h3 style='color:#ef4444;'>Erreur</h3>";
        echo "<p>Ce nom d'utilisateur ou cet e-mail est déjà utilisé !</p><br>";
        echo "<a href='inscription.php' class='btn btn-neon'>Essayer un autre nom</a>";
    } else {
        //Chiffrage mdp
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
        //Insert utilisateur dans la table client
        $sql = "INSERT INTO client (login, motDePasse, email) VALUES ('$user', '$mdp_hache', '$email')";
        //Execute requete sql
        if (mysqli_query($bdd, $sql)) {
            echo "<h3 style='color:#c8f53c;'>Inscription réussie !</h3><br>";
            echo "<a href='connexion.php' class='btn btn-neon'>Se connecter</a>";
        } else {
            echo "<h3 style='color:#ef4444;'>Erreur lors de l'inscription</h3>";
            echo "<p>" . mysqli_error($bdd) . "</p><br>";
            echo "<a href='inscription.php' class='btn btn-pill-outline'>Retour</a>";
        }
    }
} else {
    echo "<p style='color:#ef4444;'>Erreur de connexion à la base de données.</p>";
}
?>
            </div>
        </div>
    </body>
</html>