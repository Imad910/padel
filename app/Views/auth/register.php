<html>
    <head>
        <title>Inscription - PadelArena</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

        <header>
            <div class="logo">
                <a href="index.php">🎾 Padel<span>Arena</span></a>
            </div>
            <div>
                <a href="index.php" class="btn btn-pill-outline"> Retour à l'accueil</a>
            </div>
        </header>

        <div class="auth-wrapper">
            <div class="articlee">
                <h3>Inscription</h3>
                <form action="page_inscription.php" method="POST">
                    Nom
                    <input type="text" value="" name="user" placeholder="Votre nom" required/>
                    <br>
                    Adresse e-mail
                    <input type="email" value="" name="email" placeholder="nom@exemple.com"/>
                    <br>
                    Numéro de téléphone
                    <input type="tel" value="" name="telephone" placeholder="06 12 34 56 78"/>
                    <br>
                    Mots de passe
                    <input type="password" value="" name="mdp" placeholder="••••••••" required/>

                    <br><br>
                    <input type="submit" value="Valider"/>
                </form>
                <div class="auth-footer">
                    Déjà inscrit ? <a href="connexion.php">Se connecter</a>
                </div>
            </div>
        </div>

    </body>
</html>