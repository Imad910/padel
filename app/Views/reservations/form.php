<html>
<head>
    <title>Finaliser la réservation - PadelArena</title>
    <link rel="stylesheet" href="style.css?v=2">
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
            <span style="color: #c8f53c; font-weight: 700; margin-right: 12px; font-size: 0.95rem;">
                🎾 <?php echo htmlspecialchars($_SESSION['utilisateur']); ?>
            </span>
            <a href="deconnection.php" class="btn btn-danger" style="padding: 7px 18px; font-size: 0.85rem;">Déconnexion</a>
        </div>
    </header>

    <main class="reserve-container">
        <div class="reserve-card" style="max-width: 650px;">
            <h2>Finaliser votre réservation</h2>
            <p class="subtitle">Veuillez vérifier les détails de votre session.</p>

            <form action="traitement_reservation.php" method="POST">
                
                <!-- Ligne 1 : Nom et Email -->
                <div style="display: flex; gap: 15px;">
                    <div class="form-item" style="flex: 1;">
                        <label for="nom">Nom complet</label>
                        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($_SESSION['utilisateur']); ?>" required>
                    </div>
                    <div class="form-item" style="flex: 1;">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" placeholder="jean@email.com" required>
                    </div>
                </div>

                <!-- Ligne 2 : Terrain et Date -->
                <div style="display: flex; gap: 15px;">
                    <div class="form-item" style="flex: 1;">
                        <label for="terrain">Choisir terrain</label>
                        <select id="terrain" name="terrain" required onchange="window.location.href='reserver.php?terrain='+this.value+'&creneau=<?php echo rawurlencode($selectedSlot); ?>&date=<?php echo rawurlencode($date); ?>'">
                            <?php foreach ($terrains as $terrainOption): ?>
                                <option value="<?php echo (int) $terrainOption['id']; ?>" data-prix="<?php echo htmlspecialchars($terrainOption['prix']); ?>" <?php echo ((int) $terrainOption['id'] === $terrainId) ? 'selected' : ''; ?>><?php echo htmlspecialchars($terrainOption['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-item" style="flex: 1;">
                        <label for="date">Date de session</label>
                        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <!-- Ligne 3 : Créneaux et Joueurs -->
                <div style="display: flex; gap: 15px;">
                    <div class="form-item" style="flex: 1;">
                        <label for="creneau">Créneaux</label>
                        <select id="creneau" name="creneau" required>
                            <?php foreach ($allSlots as $slot): ?>
                                <?php $isBooked = in_array($slot, $bookedSlots, true); ?>
                                <option value="<?php echo htmlspecialchars($slot); ?>" <?php echo $isBooked ? 'disabled' : ''; ?> <?php echo ($slot === $selectedSlot && !$isBooked) ? 'selected' : ''; ?>><?php echo htmlspecialchars($slot); ?><?php echo $isBooked ? ' (Complet)' : ''; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-item" style="flex: 1;">
                        <label for="joueurs">Nombre de joueurs</label>
                        <select id="joueurs" name="joueurs" required>
                            <option value="1">1 joueur (Entraînement)</option>
                            <option value="2">2 joueurs</option>
                            <option value="3">3 joueurs</option>
                            <option value="4" selected>4 joueurs (Classique)</option>
                        </select>
                    </div>
                </div>

                <!-- Ligne 4 : Paiement -->
                <div class="form-item">
                    <label for="paiement">Mode de paiement</label>
                    <select id="paiement" name="paiement" required>
                        <option value="cb">Carte Bancaire (En ligne)</option>
                        <option value="sur_place">Paiement sur place au Club House</option>
                    </select>
                </div>

                <!-- Affichage du Total Estimé -->
                <div style="background: #1a1a1a; border: 1px solid #333; padding: 20px; border-radius: 8px; margin: 25px 0; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: #999; font-size: 1.1rem;">Total estimé :</span>
                    <span style="font-size: 1.6rem; font-weight: 800; color: #c8f53c;" id="total-display">0.00 €</span>
                </div>

                <!-- Bouton Confirmer -->
                <button type="submit" class="btn-neon full-width" style="padding: 16px; font-size: 1.1rem;">
                    Confirmer réservation
                </button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const terrainSelect = document.getElementById('terrain');
            const totalDisplay = document.getElementById('total-display');

            function updateTotal() {
                const selectedOption = terrainSelect.options[terrainSelect.selectedIndex];
                const prix = selectedOption.getAttribute('data-prix');
                totalDisplay.innerHTML = prix + ' €';
            }

            updateTotal();
            terrainSelect.addEventListener('change', updateTotal);
        });
    </script>
</body>
</html>