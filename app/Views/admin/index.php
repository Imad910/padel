
<html>
<head>
    <title>Administration - PadelArena</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background: #1e1e1e;
            border-radius: 10px;
            color: #fff;
        }
        h1 { color: #c8f53c; margin-bottom: 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #2a2a2a;
            border-radius: 6px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        th {
            background: #333;
            color: #c8f53c;
        }
        tr:hover { background: #323232; }
        .btn-supprimer {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-supprimer:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <a href="index.php">🎾 Padel<span>Arena</span></a>
        </div>
        <nav class="main-nav">
            <a href="index.php">Accueil</a>
            <a href="terrains.php">Réserver un terrain</a>
            <a href="mes_reservations.php">Mes réservations</a>
            <a href="admin.php" class="active" style="color: #c8f53c; font-weight: 700;">Administration</a>
        </nav>
        <div class="auth-buttons">
            <span style="color: #c8f53c; font-weight: 700; margin-right: 12px; font-size: 0.95rem;">
                👤 <?php echo htmlspecialchars($user); ?> (Admin)
            </span>
             <a href="deconnection.php" class="btn btn-danger" style="padding: 7px 18px; font-size: 0.85rem;">Déconnexion</a>
        </div>
    </header>

    <div class="admin-container">
        <h1>Panneau d'Administration - Gestion des Réservations</h1>
        <p>Retrouvez ci-dessous la liste de toutes les réservations effectuées sur le site par l'ensemble des clients.</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Terrain</th>
                    <th>Date de session</th>
                    <th>Créneau horaire</th>
                    <th>Date de réservation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) > 0): ?>
                    <?php foreach ($reservations as $row): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nom_client']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['terrain_nom']); ?> <span style="font-size: 0.8rem; color: #aaa;">(<?php echo htmlspecialchars($row['terrain_type']); ?>)</span></td>
                            <td><?php echo htmlspecialchars($row['date_session']); ?></td>
                            <td><?php echo htmlspecialchars($row['creneau']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_reservation']); ?></td>
                            <td>
                                <!-- Formulaire pour annuler/supprimer la réservation -->
                                <form method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler cette réservation ?');" style="margin: 0;">
                                    <input type="hidden" name="id_reservation" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="supprimer_reservation" class="btn-supprimer">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888;">Aucune réservation enregistrée pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>