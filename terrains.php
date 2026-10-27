<?php
//Vérifie si une session n'existe pas déja
if (session_status() === PHP_SESSION_NONE) {
    //Crée une session
    session_start();
}
require_once("fonction.php");
$bdd = connexion();

//Récupère le filtre dans l'URL
$filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'all';

if ($bdd) {
    //Vérifie type de filtre
    if ($filtre === 'indoor') {
        $rqt = "SELECT * FROM terrain WHERE type = 'Indoor';";
    } elseif ($filtre === 'outdoor') {
        $rqt = "SELECT * FROM terrain WHERE type = 'Outdoor';";
    } else {
        $rqt = "SELECT * FROM terrain;";
    }
    //Execute la requete
    $res = mysqli_query($bdd, $rqt);
}
?>
<html>
<head>
    <title>PadelArena - Terrains de padel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header complet avec navigation -->
    <header>
        <div class="logo">
            <a href="index.php">🎾 Padel<span>Arena</span></a>
        </div>
        
        <nav class="main-nav">
            <a href="index.php">Accueil</a>
            <a href="terrains.php" class="active">Réserver un terrain</a>
            <a href="mes_reservations.php">Mes réservations</a>
            
       <?php
//Menu Admin
if (isset($_SESSION['utilisateur'])) {
    if (!isset($bdd) || !$bdd) {
        $bdd = connexion();
    }
    if ($bdd) {
        //Sécurise les info de le l'utilisateur
        $u_verif = mysqli_real_escape_string($bdd, $_SESSION['utilisateur']);
        //Requete pour récuperer le role du client
        $q_admin = mysqli_query($bdd, "SELECT role FROM client WHERE email = '$u_verif' OR login = '$u_verif'");
       // Vérifie si la requete a marcher
        if ($q_admin && mysqli_num_rows($q_admin) > 0) {
            $d_admin = mysqli_fetch_assoc($q_admin);
            //Vérifie si le role est admin
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
                    <a href="terrains.php?filtre=all" class="filter-pill <?php if($filtre == 'all') echo 'active'; ?>">Tous les terrains</a>
                    <a href="terrains.php?filtre=indoor" class="filter-pill <?php if($filtre == 'indoor') echo 'active'; ?>">Intérieur</a>
                    <a href="terrains.php?filtre=outdoor" class="filter-pill <?php if($filtre == 'outdoor') echo 'active'; ?>">Extérieur</a>
                </div>
            </div>
        </div>

        <!-- Grille de terrains -->
        <section class="courts-grid">
            <?php if ($res && mysqli_num_rows($res) > 0): ?>
                <?php while ($prod = mysqli_fetch_assoc($res)): ?>
                    <div class="court-card">
                        <?php
                        $id_t = $prod['id'];
                        $date_jour = date('Y-m-d');
                        $req_resa = mysqli_query($bdd, "SELECT creneau FROM reservation WHERE terrain_id = $id_t AND date_session = '$date_jour'");
                        $creneaux_reserves = [];
                        while ($r = mysqli_fetch_assoc($req_resa)) {
                            $creneaux_reserves[] = $r['creneau'];
                        }
                        
                        $tous_les_creneaux = ["14:00", "15:00", "16:30", "18:00"];
                        $creneaux_dispo = 4 - count($creneaux_reserves);
                        ?>

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
                                <?php foreach ($tous_les_creneaux as $heure): ?>
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
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #999;">Aucun terrain trouvé pour cette catégorie.</p>
            <?php endif; ?>
        </section>

    </main>

</body>
</html>