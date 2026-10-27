:<?php
//Vérifie si une session existe déja
if (session_status() === PHP_SESSION_NONE) {
    //Démarre une session PHP
    session_start();
}
require_once("fonction.php");
$bdd = connexion();

//Vérifie si l'utilisateur n'est pas connecté et le redirige vers connexion.php
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

//Sécurise l'utilisateur
$utilisateur = mysqli_real_escape_string($bdd, $_SESSION['utilisateur']);

//Menu annulation
if (isset($_POST['annuler_resa'])) {
    //Recupere la réservation a supprimer
    $id_a_supprimer = intval($_POST['id_resa']);
    
    //Vérifie que la réservation appartient à l'utilisateur connecté avant de supprimer
    $rqt_delete = "DELETE FROM reservation WHERE id = $id_a_supprimer AND nom_client = '$utilisateur'";
    mysqli_query($bdd, $rqt_delete);
    
    //On recharge la page 
    header("Location: mes_reservations.php");
    exit();
}

// Récupération des réservations
if ($bdd) {
    //Requete sql pour recuperer reservation du client
    $rqt = "SELECT r.*, t.nom AS terrain_nom, t.image_url 
            FROM reservation r 
            JOIN terrain t ON r.terrain_id = t.id 
            WHERE r.nom_client = '$utilisateur' 
            ORDER BY r.date_session ASC, r.creneau ASC";
    //Execute la requete
    $res = mysqli_query($bdd, $rqt);
}
?>

<html>
<head>
    <title>Mes Réservations - PadelArena</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        .reservation-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        .reservation-card {
            display: flex;
            background: #242424;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #333;
            height: 180px; /* Force la hauteur de la carte */
        }
        .res-img-wrap {
            width: 250px;
            height: 100%;
            flex-shrink: 0;
        }
        .res-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .res-details {
            padding: 24px;
            flex-grow: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .res-info h3 {
            color: #fff;
            margin-bottom: 8px;
            font-size: 1.3rem;
        }
        .res-info p {
            color: #cbd5e1;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }
        .res-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 15px;
        }
        .badge-status {
            background: rgba(200, 245, 60, 0.15);
            color: #c8f53c;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid rgba(200, 245, 60, 0.3);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .btn-cancel {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            font-size: 0.85rem;
        }
        .btn-cancel:hover {
            background: #ef4444;
            color: #fff;
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
            <a href="mes_reservations.php" class="active">Mes réservations</a>
        <?php
        //Menu Admin
        //Vérifie si une session est active 
        if (isset($_SESSION['utilisateur'])) {
            if (!isset($bdd) || !$bdd) {
                $bdd = connexion();
            }
            if ($bdd) {
                //Sécurise les informations de l'utilisateur
                $u_verif = mysqli_real_escape_string($bdd, $_SESSION['utilisateur']);
                //Récupere le role du client
                $q_admin = mysqli_query($bdd, "SELECT role FROM client WHERE email = '$u_verif' OR login = '$u_verif'");
                //Vérifie si la requete a marcher
                if ($q_admin && mysqli_num_rows($q_admin) > 0) {
                    $d_admin = mysqli_fetch_assoc($q_admin);
                    //Vérifie si le role est bien admin
                    if ($d_admin && isset($d_admin['role']) && $d_admin['role'] === 'admin') {
                        //Affiche le menu admin
                        echo '<a href="admin.php" style="color: #c8f53c; font-weight: 700;">Administration</a>';
                    }
                }
            }
        }
        ?>
        </nav>
        <div class="auth-buttons">
            <span style="color: #c8f53c; font-weight: 700; margin-right: 12px; font-size: 0.95rem;">
                🎾 <?php echo htmlspecialchars($_SESSION['utilisateur']); ?>
            </span>
            <a href="deconnection.php" class="btn btn-danger" style="padding: 7px 18px; font-size: 0.85rem;">Déconnexion</a>
        </div>
    </header>

    <main class="main-layout">
        <div class="search-header" style="max-width: 900px; margin: 0 auto 30px auto;">
            <h1 style="color: #fff; font-size: 2rem; margin-bottom: 10px;">Mes réservations</h1>
            <p style="color: #999;">Retrouvez l'historique de vos sessions prévues au club PadelArena.</p>
        </div>

        <div class="reservation-list">
            <?php if ($res && mysqli_num_rows($res) > 0): ?>
                <?php while ($resa = mysqli_fetch_assoc($res)): ?>
                    
                    <div class="reservation-card">
                        <div class="res-img-wrap">
                            <img src="<?php echo htmlspecialchars($resa['image_url']); ?>" alt="<?php echo htmlspecialchars($resa['terrain_nom']); ?>">
                        </div>
                        
                        <div class="res-details">
                            <div class="res-info">
                                <h3><?php echo htmlspecialchars($resa['terrain_nom']); ?></h3>
                                <p>📅 <strong>Date :</strong> <?php echo date('d/m/Y', strtotime($resa['date_session'])); ?></p>
                                <p>⏰ <strong>Créneau :</strong> <?php echo htmlspecialchars($resa['creneau']); ?></p>
                                <p>💳 <strong>Montant :</strong> <?php echo htmlspecialchars($resa['total']); ?> €</p>
                            </div>
                            
                            <div class="res-actions">
                                <span class="badge-status">Confirmée</span>
                                
                                <!-- Bouton d'annulation qui envoie l'ID à supprimer -->
                                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                    <input type="hidden" name="id_resa" value="<?php echo $resa['id']; ?>">
                                    <button type="submit" name="annuler_resa" class="btn-cancel">Annuler la session</button>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div style="background: #242424; padding: 40px; border-radius: 12px; text-align: center; border: 1px dashed #444;">
                    <p style="color: #ccc; font-size: 1.1rem; margin-bottom: 20px;">Vous n'avez aucune réservation pour le moment.</p>
                    <a href="terrains.php" class="btn-neon">Réserver mon premier terrain</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>