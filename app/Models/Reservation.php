<?php

require_once __DIR__ . '/Database.php';

class Reservation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findBookedSlots($terrainId, $date)
    {
        $statement = $this->db->prepare(
            'SELECT creneau FROM reservation WHERE terrain_id = ? AND date_session = ?'
        );
        $statement->bind_param('is', $terrainId, $date);
        $statement->execute();
        $rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'creneau');
    }

    public function findForClient($clientId, $login)
    {
        $statement = $this->db->prepare(
            'SELECT r.*, t.nom AS terrain_nom, t.image_url
             FROM reservation r
             JOIN terrain t ON r.terrain_id = t.id
             WHERE r.client_id = ? OR (r.client_id IS NULL AND r.nom_client = ?)
             ORDER BY r.date_session ASC, r.creneau ASC'
        );
        $statement->bind_param('is', $clientId, $login);
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function cancelForClient($reservationId, $clientId, $login)
    {
        $statement = $this->db->prepare(
            'DELETE FROM reservation
             WHERE id = ? AND (client_id = ? OR (client_id IS NULL AND nom_client = ?))'
        );
        $statement->bind_param('iis', $reservationId, $clientId, $login);
        return $statement->execute();
    }

    public function create($clientId, $name, $email, $terrainId, $date, $slot, $total)
    {
        $rackets = 0;
        $statement = $this->db->prepare(
            'INSERT INTO reservation
             (client_id, terrain_id, nom_client, email_client, date_session, creneau, nb_raquettes, total)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param('iissssid', $clientId, $terrainId, $name, $email, $date, $slot, $rackets, $total);
        return $statement->execute();
    }

    public function findAll()
    {
        $result = $this->db->query(
            'SELECT r.*, t.nom AS terrain_nom, t.type AS terrain_type
             FROM reservation r
             JOIN terrain t ON r.terrain_id = t.id
             ORDER BY r.date_session DESC, r.creneau ASC'
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($reservationId)
    {
        $statement = $this->db->prepare('DELETE FROM reservation WHERE id = ?');
        $statement->bind_param('i', $reservationId);
        return $statement->execute();
    }
}