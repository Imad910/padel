<?php

require_once __DIR__ . '/../Models/Client.php';

class HomeController
{
    public function index()
    {
        $client = isset($_SESSION['utilisateur'])
            ? (new Client())->findByLogin($_SESSION['utilisateur'])
            : null;
        $isAdmin = $client && ($client['role'] ?? '') === 'admin';
        require __DIR__ . '/../Views/accueil/index.php';
    }
}