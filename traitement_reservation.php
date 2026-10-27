<?php
//Vérifie si une session n'existe pas déja
if (session_status() === PHP_SESSION_NONE) {
    //Crée une session
    session_start();
}
require_once("fonction.php");
$bdd = connexion();

//On vérifie que la page reçoit les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //Récupére et sécurise les données envoyées (contre le piratage)
    $nom_client = mysqli_real_escape_string($bdd, $_POST['nom']);
    $email_client = mysqli_real_escape_string($bdd, $_POST['email']);
    $terrain_id = intval($_POST['terrain']);
    $date_session = mysqli_real_escape_string($bdd, $_POST['date']);
    $creneau = mysqli_real_escape_string($bdd, $_POST['creneau']);
    
    // Ta base de données attend "nb_raquettes". On met 0 par défaut pour l'instant
    $nb_raquettes = 0; 

    //Cherche le vrai prix du terrain dans la BDD (sécurité)
    $req_prix = mysqli_query($bdd, "SELECT prix FROM terrain WHERE id = $terrain_id");
    if ($terrain = mysqli_fetch_assoc($req_prix)) {
        $total = $terrain['prix'];
    } else {
        $total = 0;
    }

    //Requête d'insertion
    $rqt = "INSERT INTO reservation (terrain_id, nom_client, email_client, date_session, creneau, nb_raquettes, total) 
            VALUES ($terrain_id, '$nom_client', '$email_client', '$date_session', '$creneau', $nb_raquettes, $total)";

    //Execute la requête
    if (mysqli_query($bdd, $rqt)) {
        $success = true;
        $message = "Votre réservation a été confirmée avec succès !";
    } else {
        $success = false;
        $message = "Erreur lors de la réservation : " . mysqli_error($bdd);
    }
} else {
    //Si quelqu'un accède à la page sans valider le formulaire, on le renvoie à l'accueil
    header("Location: terrains.php");
    exit();
}
?>
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
                <p style="color: #fff; font-size: 1.1rem; margin-bottom: 25px;">Merci <strong><?php echo htmlspecialchars($nom_client); ?></strong>, votre piste vous attend.</p>
                
                <div style="background: #1a1a1a; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: left; border: 1px solid #333;">
                    <p style="color: #ccc; margin-bottom: 8px;">📅 <strong>Date :</strong> <?php echo htmlspecialchars($date_session); ?></p>
                    <p style="color: #ccc; margin-bottom: 8px;">⏰ <strong>Créneau :</strong> <?php echo htmlspecialchars($creneau); ?></p>
                    <p style="color: #ccc; margin-bottom: 8px;">📧 <strong>Contact :</strong> <?php echo htmlspecialchars($email_client); ?></p>
                    <p style="color: #c8f53c; font-weight: 700; margin-top: 15px; border-top: 1px solid #333; padding-top: 15px;">Total à régler sur place : <?php echo $total; ?> €</p>
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