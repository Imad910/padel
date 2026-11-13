<?php

require_once __DIR__ . '/Database.php';

class Client
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByEmail($email)
    {
        $statement = $this->db->prepare('SELECT id, login, motDePasse, role FROM client WHERE email = ?');
        $statement->bind_param('s', $email);
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    }

    public function findByLogin($login)
    {
        $statement = $this->db->prepare('SELECT id, login, role FROM client WHERE login = ?');
        $statement->bind_param('s', $login);
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    }

    public function exists($login, $email)
    {
        $statement = $this->db->prepare('SELECT id FROM client WHERE login = ? OR email = ?');
        $statement->bind_param('ss', $login, $email);
        $statement->execute();
        return $statement->get_result()->num_rows > 0;
    }

    public function create($login, $password, $email, $telephone)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $statement = $this->db->prepare(
            'INSERT INTO client (login, motDePasse, email, telephone) VALUES (?, ?, ?, ?)'
        );
        $statement->bind_param('ssss', $login, $hash, $email, $telephone);
        return $statement->execute();
    }
}