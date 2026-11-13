<?php

require_once __DIR__ . '/Database.php';

class Terrain
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll($filter = 'all')
    {
        if ($filter === 'indoor' || $filter === 'outdoor') {
            $type = $filter === 'indoor' ? 'Indoor' : 'Outdoor';
            $statement = $this->db->prepare('SELECT * FROM terrain WHERE type = ? ORDER BY id');
            $statement->bind_param('s', $type);
        } else {
            $statement = $this->db->prepare('SELECT * FROM terrain ORDER BY id');
        }

        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function find($id)
    {
        $statement = $this->db->prepare('SELECT * FROM terrain WHERE id = ?');
        $statement->bind_param('i', $id);
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    }
}