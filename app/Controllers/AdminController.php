<?php

require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/../Models/Reservation.php';

class AdminController
{
    public function index()
    {
        if (!isset($_SESSION['utilisateur'])) {
            header('Location: connexion.php');
            exit();
        }

        $client = (new Client())->findByLogin($_SESSION['utilisateur']);
        if (!$client || ($client['role'] ?? '') !== 'admin') {
            header('Location: index.php');
            exit();
        }

        $reservationModel = new Reservation();
        if (isset($_POST['supprimer_reservation'])) {
            $reservationId = filter_input(INPUT_POST, 'id_reservation', FILTER_VALIDATE_INT) ?: 0;
            if ($reservationId > 0) {
            $reservationModel->delete($reservationId);
            }
            header('Location: admin.php');
            exit();
        }

        $reservations = $reservationModel->findAll();
        $user = $_SESSION['utilisateur'];
        require __DIR__ . '/../Views/admin/index.php';
    }
}