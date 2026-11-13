<?php

require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/../Models/Terrain.php';
require_once __DIR__ . '/../Models/Reservation.php';

class ReservationController
{
    private $clients;
    private $terrains;
    private $reservations;

    public function __construct()
    {
        $this->clients = new Client();
        $this->terrains = new Terrain();
        $this->reservations = new Reservation();
    }

    public function form()
    {
        $this->requireLogin();
        $terrainId = filter_input(INPUT_GET, 'terrain', FILTER_VALIDATE_INT) ?: 0;
        $terrain = $this->terrains->find($terrainId);
        if (!$terrain) {
            header('Location: terrains.php');
            exit();
        }

        $date = $_GET['date'] ?? date('Y-m-d');
        $selectedSlot = $_GET['creneau'] ?? '';
        $terrains = $this->terrains->findAll();
        $bookedSlots = $this->reservations->findBookedSlots($terrainId, $date);
        $allSlots = ['14:00', '15:00', '16:30', '18:00'];
        $client = $this->clients->findByLogin($_SESSION['utilisateur']);
        $isAdmin = $client && ($client['role'] ?? '') === 'admin';
        require __DIR__ . '/../Views/reservations/form.php';
    }

    public function store()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: terrains.php');
            exit();
        }

        $terrainId = filter_input(INPUT_POST, 'terrain', FILTER_VALIDATE_INT) ?: 0;
        $terrain = $this->terrains->find($terrainId);
        $name = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $date = $_POST['date'] ?? '';
        $slot = $_POST['creneau'] ?? '';
        $allowedSlots = ['14:00', '15:00', '16:30', '18:00'];
        $message = '';
        $success = false;

        if (!$terrain || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || !in_array($slot, $allowedSlots, true) || !$this->isValidDate($date) || $date < date('Y-m-d')) {
            $message = 'Les informations de réservation sont invalides.';
        } elseif (in_array($slot, $this->reservations->findBookedSlots($terrainId, $date), true)) {
            $message = 'Ce créneau vient d’être réservé. Veuillez en choisir un autre.';
        } else {
            $client = $this->clients->findByLogin($_SESSION['utilisateur']);
            if ($client) {
                $success = $this->reservations->create(
                    (int) $client['id'], $name, $email, $terrainId, $date, $slot, (float) $terrain['prix']
                );
                $message = $success
                    ? 'Votre réservation a été confirmée avec succès !'
                    : 'Une erreur est survenue pendant la réservation.';
            } else {
                $message = 'Votre compte n’a pas été retrouvé. Veuillez vous reconnecter.';
            }
        }

        require __DIR__ . '/../Views/reservations/confirmation.php';
    }

    public function mine()
    {
        $this->requireLogin();
        $client = $this->clients->findByLogin($_SESSION['utilisateur']);
        if (isset($_POST['annuler_resa'])) {
            $reservationId = filter_input(INPUT_POST, 'id_resa', FILTER_VALIDATE_INT) ?: 0;
            if ($client && $reservationId > 0) {
                $this->reservations->cancelForClient($reservationId, (int) $client['id'], $_SESSION['utilisateur']);
            }
            header('Location: mes_reservations.php');
            exit();
        }

        $reservations = $client
            ? $this->reservations->findForClient((int) $client['id'], $_SESSION['utilisateur'])
            : [];
        $user = $_SESSION['utilisateur'];
        $isAdmin = $client && ($client['role'] ?? '') === 'admin';
        require __DIR__ . '/../Views/reservations/mine.php';
    }

    private function requireLogin()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: connexion.php');
            exit();
        }
    }

    private function isValidDate($date)
    {
        $value = DateTime::createFromFormat('Y-m-d', $date);
        return $value && $value->format('Y-m-d') === $date;
    }
}