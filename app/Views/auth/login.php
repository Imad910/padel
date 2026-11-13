<html>
    <head>
        <title>Connexion</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <div class="logo">
                <a href="index.php">🎾 Padel<span>Arena</span></a>
            </div>
            <div>
                <a href="index.php" class="btn btn-pill-outline">Retour à l'accueil</a>
            </div>
        </header>

        <div class="auth-wrapper">
            <div class="articlee">
                <h3>Connexion</h3>
   
                <?php if (isset($erreur)): ?>
                    <p style="color: #ef4444; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px; font-weight: 600;">
                        <?php echo htmlspecialchars($erreur); ?>
                    </p>
                <?php endif; ?>

                <form action="page_connexion.php" method="POST">
                    Adresse e-mail
                    <input type="email" value="" name="email" placeholder="nom@exemple.com" required/>
                    <br>
                    Mots de passe
                    <input type="password" value="" name="mdp" required/>

                    <br>
                    <input type="submit" name="connexion" value="Connexion"/>
                </form>
                <div class="auth-footer">
                    Pas encore membre ? <a href="inscription.php">Créer un compte</a>
                </div>
            </div>
        </div>
    </body>
</html>