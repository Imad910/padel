<html>
<head>
    <title>PadelArena - Le Complexe Premium</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="logo">
            <a href="index.php">🎾 Padel<span>Arena</span></a>
        </div>
        
       <!-- Onglets de navigation au centre -->
       <nav class="main-nav">
            <a href="index.php">Accueil</a>
            <a href="terrains.php">Réserver un terrain</a>
            <a href="mes_reservations.php">Mes réservations</a>
    
            <?php if ($isAdmin): ?>
                <a href="admin.php" style="color: #c8f53c; font-weight: 700;">Administration</a>
            <?php endif; ?>
  </nav>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <span style="color: #c8f53c; font-weight: 700; margin-right: 12px; font-size: 0.95rem;">
                    🎾 <?php echo htmlspecialchars($_SESSION['utilisateur']); ?>
                </span>
                <a href="deconnection.php" class="btn btn-danger" style="padding: 7px 18px; font-size: 0.85rem;">Déconnexion</a>
            <?php else: ?>
                <a href="connexion.php" class="btn btn-pill-outline">Connexion</a>
                <a href="inscription.php" class="btn btn-neon">Inscription</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <!-- Grande bannière d'accueil -->
        <section class="hero-landing">
            <div class="hero-content">
                <h1>Le padel réinventé en Île-de-France</h1>
                <p>Découvrez des installations premium, des pistes panoramiques dernière génération et une ambiance unique. Rejoignez la communauté PadelArena.</p>
                <div class="hero-buttons">
                    <a href="terrains.php" class="btn btn-neon btn-lg">Voir les terrains disponibles</a>
                </div>
            </div>
        </section>

        <!-- Section de présentation -->
        <section id="presentation" class="club-presentation">
            <div class="presentation-inner">
                <div class="text-box">
                    <h2>Des infrastructures de pointe</h2>
                    <p>Nous mettons à votre disposition 8 pistes indoor et outdoor. Nos terrains panoramiques offrent un confort de jeu inégalé, avec une moquette sans sable apparent et un éclairage LED anti-éblouissement.</p>
                    <ul class="feature-list">
                        <li>✔️ Vestiaires modernes et équipés</li>
                        <li>✔️ Club House avec bar et restauration</li>
                        <li>✔️ Location de matériel Pro Shop</li>
                        <li>✔️ Parking privé sécurisé</li>
                    </ul>
                </div>
                <div class="image-box">
                    <img src="https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?auto=format&fit=crop&w=1000&q=80" alt="Club PadelArena">
                </div>
            </div>
        </section>
    </main>

</body>
</html>