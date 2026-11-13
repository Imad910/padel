<?php

require_once __DIR__ . '/../Models/Terrain.php';
require_once __DIR__ . '/../Models/Reservation.php';
require_once __DIR__ . '/../Models/Client.php';

class TerrainController
{
    private $terrains;
    private $reservations;

    public function __construct()
    {
        $this->terrains = new Terrain();
        $this->reservations = new Reservation();
    }

    public function index()
    {
        $filter = $_GET['filtre'] ?? 'all';
        $terrains = $this->terrains->findAll($filter);
        $slotsByTerrain = [];
        $today = date('Y-m-d');

        foreach ($terrains as $terrain) {
            $slotsByTerrain[$terrain['id']] = $this->reservations->findBookedSlots($terrain['id'], $today);
        }

        $allSlots = ['14:00', '15:00', '16:30', '18:00'];
        $isAdmin = $this->isAdmin();
        require __DIR__ . '/../Views/terrains/index.php';
    }

    public function details()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
        $terrain = $this->terrains->find($id);
        if (!$terrain) {
            header('Location: terrains.php');
            exit();
        }

        $bookedSlots = $this->reservations->findBookedSlots($id, date('Y-m-d'));
        $allSlots = ['14:00', '15:00', '16:30', '18:00'];
        $isAdmin = $this->isAdmin();
        require __DIR__ . '/../Views/terrains/details.php';
    }

    private function isAdmin()
    {
        if (!isset($_SESSION['utilisateur'])) {
            return false;
        }

        $client = (new Client())->findByLogin($_SESSION['utilisateur']);
        return $client && ($client['role'] ?? '') === 'admin';
    }
}