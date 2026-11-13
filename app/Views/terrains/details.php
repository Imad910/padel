<html>
<head>
    <title><?php echo htmlspecialchars($terrain['nom']); ?> - PadelArena</title>
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

    <main class="details-container">
        <!-- Lien de retour -->
        <div class="back-link">
            <a href="terrains.php">← Retour aux terrains</a>
        </div>

        <!-- Grille principale de détails -->
        <div class="details-grid">
            
            <!-- Colonne Gauche : Image et Informations du terrain -->
            <div class="details-main-content">
                <div class="details-image-card">
                    <img src="<?php echo htmlspecialchars($terrain['image_url']); ?>" alt="<?php echo htmlspecialchars($terrain['nom']); ?>">
                </div>
                
                <div class="details-info-box">
                    <span class="badge-type"><?php echo htmlspecialchars($terrain['type']); ?></span>
                    <h1><?php echo htmlspecialchars($terrain['nom']); ?></h1>
                    <p class="details-address">📍 12 Avenue de la Porte d'Auteuil, 75016 Paris</p>
                </div>

                <div class="details-description-box">
                    <h3>À propos de ce terrain</h3>
                    <p><?php echo htmlspecialchars($terrain['description'] ?? 'Terrain de qualité supérieure avec éclairage LED optimal et vitres panoramiques.'); ?></p>
                    
                    <ul class="features-list">
                        <li>✔️️ Vestiaires chauffés</li>
                        <li>✔️ Prêt de balles inclus</li>
                        <li>✔️ Espace détente</li>
                        <li>✔️ Taille réglementaire (20m x 10m)</li>
                    </ul>
                </div>
            </div>

            <!-- Colonne Droite : Panneau de réservation dynamique -->
            <div class="details-sidebar">
                <div class="sidebar-box">
                    <p class="sidebar-club">PadelArena - Paris 16</p>
                    <div class="sidebar-price">
                        <?php echo htmlspecialchars($terrain['prix']); ?> € <span>/ heure</span>
                    </div>
                    
                    <p class="sidebar-note" style="margin-bottom: 20px; font-weight: 600; color: #fff;">
                        Sélectionnez votre créneau :
                    </p>
                    
                    <!-- Grille des créneaux cliquables ou grisés -->
                    <div class="slots-grid" style="grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <?php foreach ($allSlots as $heure):
                            if (in_array($heure, $bookedSlots, true)):
                        ?>
                            <span class="slot-item slot-booked" style="padding: 12px;">
                                <span class="slot-time" style="font-size: 1rem;"><?php echo $heure; ?></span>
                                <span class="slot-price" style="color:#ef4444;">Complet</span>
                            </span>
                        <?php else: ?>
                            <a href="reserver.php?terrain=<?php echo $terrain['id']; ?>&creneau=<?php echo $heure; ?>" class="slot-item" style="padding: 12px;">
                                <span class="slot-time" style="font-size: 1rem;"><?php echo $heure; ?></span>
                                <span class="slot-price"><?php echo htmlspecialchars($terrain['prix']); ?>€</span>
                            </a>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>