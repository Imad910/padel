
-- 1. Table des utilisateurs / clients
CREATE TABLE IF NOT EXISTS client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(100) NOT NULL UNIQUE,
    motDePasse VARCHAR(255) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    telephone VARCHAR(20) DEFAULT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Table des terrains de padel
CREATE TABLE IF NOT EXISTS terrain (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,             -- Ex: 'Indoor', 'Outdoor'
    description TEXT DEFAULT NULL,
    taille VARCHAR(50) DEFAULT '20m x 10m',
    prix DECIMAL(6, 2) NOT NULL,          -- Ex: 36.00, 32.00, 28.00
    image_url VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

-- 3. Table des réservations
CREATE TABLE IF NOT EXISTS reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT DEFAULT NULL,
    terrain_id INT NOT NULL,
    nom_client VARCHAR(100) NOT NULL,
    email_client VARCHAR(150) NOT NULL,
    date_session DATE NOT NULL,
    creneau VARCHAR(50) NOT NULL,          -- Ex: '09:00 - 10:30'
    nb_raquettes INT DEFAULT 0,
    total DECIMAL(6, 2) NOT NULL,
    statut VARCHAR(30) DEFAULT 'Confirmée', -- 'Confirmée', 'Annulée'
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservation_terrain FOREIGN KEY (terrain_id) REFERENCES terrain(id) ON DELETE CASCADE,
    CONSTRAINT fk_reservation_client FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Insertion des 3 terrains par défaut
INSERT INTO terrain (id, nom, type, description, taille, prix, image_url) VALUES
(1, 'Piste 1 - Centrale', 'Indoor', 'Court couvert panoramique avec caméras d enregistrement de match.', '20m x 10m', 36.00, 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?auto=format&fit=crop&w=700&q=80'),
(2, 'Piste 2 - Classic', 'Indoor', 'Court couvert standard, éclairage LED compétition.', '20m x 10m', 32.00, 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?auto=format&fit=crop&w=700&q=80'),
(3, 'Piste 3 - Plein Air', 'Outdoor', 'Terrain extérieur idéal pour les journées ensoleillées.', '20m x 10m', 28.00, 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?auto=format&fit=crop&w=700&q=80')
ON DUPLICATE KEY UPDATE nom=VALUES(nom);