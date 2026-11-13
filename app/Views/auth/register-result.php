<html>
<head>
    <title>Inscription - PadelArena</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="articlee" style="text-align: center;">
            <h3 style="color: <?php echo $success ? '#c8f53c' : '#ef4444'; ?>;">
                <?php echo $success ? 'Inscription réussie !' : 'Erreur lors de l’inscription'; ?>
            </h3>
            <p><?php echo htmlspecialchars($message); ?></p>
            <?php if ($success): ?>
                <a href="connexion.php" class="btn btn-neon">Se connecter</a>
            <?php else: ?>
                <a href="inscription.php" class="btn btn-pill-outline">Retour</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>