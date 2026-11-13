<html>
<head>
    <title>PadelArena - Terrains de padel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="logo">
            <a href="index.php">🎾 Padel<span>Arena</span></a>
        </div>
        <nav class="main-nav">
            <a href="index.php">Accueil</a>
            <a href="terrains.php" class="active">Réserver un terrain</a>
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

    <main class="main-layout">
        
        <!-- En-tête et filtres PHP -->
        <div class="search-header">
            <div class="filters-bar">
                <div class="filter-group">
                    <a href="terrains.php?filtre=all" class="filter-pill <?php if($filter == 'all') echo 'active'; ?>">Tous les terrains</a>
                    <a href="terrains.php?filtre=indoor" class="filter-pill <?php if($filter == 'indoor') echo 'active'; ?>">Intérieur</a>
                    <a href="terrains.php?filtre=outdoor" class="filter-pill <?php if($filter == 'outdoor') echo 'active'; ?>">Extérieur</a>
                </div>
            </div>
        </div>

        <!-- Grille de terrains -->
        <section class="courts-grid">
            <?php if (count($terrains) > 0): ?>
                <?php foreach ($terrains as $prod): ?>
                    <div class="court-card">
                        <?php $creneaux_reserves = $slotsByTerrain[$prod['id']] ?? []; ?>
                        <?php $creneaux_dispo = count($allSlots) - count($creneaux_reserves); ?>

                        <a href="details.php?id=<?php echo $prod['id']; ?>" style="text-decoration: none; color: inherit; display: block;">
                            <div class="card-image-wrap">
                                <span class="badge-distance">📍 PadelArena</span>
                                <span class="badge-fav">♡</span>
                                <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" alt="<?php echo htmlspecialchars($prod['nom']); ?>">
                                <div class="card-image-overlay">
                                    <span class="badge-club">Super club</span>
                                    <div class="overlay-price">
                                        <span class="price-from">À partir de</span>
                                        <span class="price-amount"><?php echo htmlspecialchars($prod['prix']); ?>€/heure</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body" style="padding-bottom: 5px;">
                                <h3><?php echo htmlspecialchars($prod['nom']); ?></h3>
                                <p class="slots-info"><?php echo $creneaux_dispo; ?> créneaux disponibles</p>
                            </div>
                        </a>
                        
                        <!-- Horaires Dynamiques -->
                        <div class="card-body" style="padding-top: 0;">
                            <div class="slots-grid">
                                    <?php foreach ($allSlots as $heure): ?>
                                    <?php if (in_array($heure, $creneaux_reserves)): ?>
                                        <span class="slot-item slot-booked">
                                            <span class="slot-time"><?php echo $heure; ?></span>
                                            <span class="slot-price" style="color:#ef4444;">Complet</span>
                                        </span>
                                    <?php else: ?>
                                        <a href="reserver.php?terrain=<?php echo $prod['id']; ?>&creneau=<?php echo $heure; ?>" class="slot-item">
                                            <span class="slot-time"><?php echo $heure; ?></span>
                                            <span class="slot-price"><?php echo htmlspecialchars($prod['prix']); ?>€</span>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #999;">Aucun terrain trouvé pour cette catégorie.</p>
            <?php endif; ?>
        </section>

    </main>

</body>
</html>