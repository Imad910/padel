<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/Controllers/HomeController.php';
require_once __DIR__ . '/Controllers/TerrainController.php';
require_once __DIR__ . '/Controllers/ReservationController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/AdminController.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'terrains':
        (new TerrainController())->index();
        break;
    case 'terrain-details':
        (new TerrainController())->details();
        break;
    case 'reservation-form':
        (new ReservationController())->form();
        break;
    case 'reservation-store':
        (new ReservationController())->store();
        break;
    case 'reservations-mine':
        (new ReservationController())->mine();
        break;
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'admin':
        (new AdminController())->index();
        break;
    case 'home':
    default:
        (new HomeController())->index();
}