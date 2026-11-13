<html>
<head>
    <title>Confirmation - PadelArena</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">🎾 Padel<span>Arena</span></a>
        </div>
        <div class="auth-buttons">
            <a href="index.php" class="btn-pill-outline">Retour à l'accueil</a>
        </div>
    </header>

    <main class="reserve-container">
        <div class="reserve-card" style="text-align: center; max-width: 550px;">
            <?php if ($success): ?>
                <h2 style="color: #c8f53c; font-size: 2.2rem; margin-bottom: 10px;">✅ Réservation Confirmée !</h2>
                <p style="color: #fff; font-size: 1.1rem; margin-bottom: 25px;">Merci <strong><?php echo htmlspecialchars($name); ?></strong>, votre piste vous attend.</p>
                
                <div style="background: #1a1a1a; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: left; border: 1px solid #333;">
                    <p style="color: #ccc; margin-bottom: 8px;">📅 <strong>Date :</strong> <?php echo htmlspecialchars($date); ?></p>
                    <p style="color: #ccc; margin-bottom: 8px;">⏰ <strong>Créneau :</strong> <?php echo htmlspecialchars($slot); ?></p>
                    <p style="color: #ccc; margin-bottom: 8px;">📧 <strong>Contact :</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p style="color: #c8f53c; font-weight: 700; margin-top: 15px; border-top: 1px solid #333; padding-top: 15px;">Total à régler sur place : <?php echo htmlspecialchars($terrain['prix']); ?> €</p>
                </div>
                
                <a href="terrains.php" class="btn-neon" style="display: inline-block; padding: 12px 30px;">Nouvelle réservation</a>
            
            <?php else: ?>
                <h2 style="color: #ef4444; font-size: 2.2rem; margin-bottom: 10px;">❌ Oups, une erreur est survenue.</h2>
                <p style="color: #ccc; margin-bottom: 25px;"><?php echo $message; ?></p>
                <a href="terrains.php" class="btn-danger" style="display: inline-block; padding: 12px 30px;">Réessayer</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>