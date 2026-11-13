<?php

require_once __DIR__ . '/../Models/Client.php';

class AuthController
{
    private $clients;

    public function __construct()
    {
        $this->clients = new Client();
    }

    public function login()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['mdp'] ?? '';
            $client = $this->clients->findByEmail($email);

            if ($client && (password_verify($password, $client['motDePasse'])
                || hash_equals($client['motDePasse'], $password))) {
                session_regenerate_id(true);
                $_SESSION['utilisateur'] = $client['login'];
                $_SESSION['client_id'] = (int) $client['id'];
                $_SESSION['role'] = $client['role'] ?? 'client';
                header('Location: index.php');
                exit();
            }

            $error = 'Email ou mot de passe incorrect !';
        }

        $erreur = $error;
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../Views/auth/register.php';
            return;
        }

        $success = false;
        $message = '';
        $login = trim($_POST['user'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $password = $_POST['mdp'] ?? '';

        if ($login === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $message = 'Veuillez renseigner un nom, une adresse e-mail valide et un mot de passe.';
        } elseif ($this->clients->exists($login, $email)) {
            $message = 'Ce nom d’utilisateur ou cet e-mail est déjà utilisé.';
        } else {
            $success = $this->clients->create($login, $password, $email, $telephone);
            $message = $success ? 'Inscription réussie !' : 'Erreur lors de l’inscription.';
        }

        require __DIR__ . '/../Views/auth/register-result.php';
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
        exit();
    }
}