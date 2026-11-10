<?php
//Vérifie si une session n'existe pas déja
if (session_status() === PHP_SESSION_NONE) {
    //Crée une session
    session_start();
}

require_once("fonction.php");
$bdd = connexion();

//Vérifie si l'utilisateur est connecté et s'il est bien admin
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

//Sécurise les info de l'utilisateur 
$u_connecte = mysqli_real_escape_string($bdd, $_SESSION['utilisateur']);
//Récupere le role du client connecté
$q_verif = mysqli_query($bdd, "SELECT role FROM client WHERE email = '$u_connecte' OR login = '$u_connecte'");
$d_verif = mysqli_fetch_assoc($q_verif);

// Si ce n'est pas un admin, on le redirige vers l'accueil
if (!$d_verif || $d_verif['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

//Menu annulation
//Vérifie le formulaire 
if (isset($_POST['supprimer_reservation'])) {
    // Récupère et sécurise l'ID de la réservation à supprimer
    $id_resa = intval($_POST['id_reservation']);
    //Requête de suppression de la réservation
    $rqt_del = "DELETE FROM reservation WHERE id = $id_resa";
    //Execute la requete
    mysqli_query($bdd, $rqt_del);
    header("Location: admin.php");
    exit();
}

//MENU RÉCUPÉRATION DE TOUTES LES RÉSERVATIONS AVEC LES INFOS DES TERRAINS
//Requete pour récuperer toute les réservations et info des terrain
$query_resas = "SELECT r.*, t.nom AS terrain_nom, t.type AS terrain_type 
                FROM reservation r 
                JOIN terrain t ON r.terrain_id = t.id 
                ORDER BY r.date_session DESC, r.creneau ASC";
//Execute la requete
$result_resas = mysqli_query($bdd, $query_resas);
?>

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
                👤 <?php echo htmlspecialchars($_SESSION['utilisateur']); ?> (Admin)
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
                <?php if ($result_resas && mysqli_num_rows($result_resas) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_resas)): ?>
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
                    <?php endwhile; ?>
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