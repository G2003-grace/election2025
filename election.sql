-- 1️⃣ Créer la base de données
CREATE DATABASE IF NOT EXISTS election2025;

-- 2️⃣ Utiliser cette base
USE election2025;

-- 3️⃣ Créer la table candidats
CREATE TABLE candidats ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nom VARCHAR(100) NOT NULL, 
    prenom VARCHAR(100) NOT NULL, 
    date_naissance DATE NOT NULL, 
    parti_politique VARCHAR(150) NOT NULL, 
    photo VARCHAR(255), 
    date_enregistrement DATETIME DEFAULT CURRENT_TIMESTAMP 
);

-- 4️⃣ Créer la table départements
CREATE TABLE departements ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nom VARCHAR(100) NOT NULL 
);

-- 5️⃣ Créer la table scores
CREATE TABLE scores ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    candidat_id INT NOT NULL, 
    departement_id INT NOT NULL, 
    voix INT NOT NULL, 
    FOREIGN KEY (candidat_id) REFERENCES candidats(id) ON DELETE CASCADE, 
    FOREIGN KEY (departement_id) REFERENCES departements(id) ON DELETE CASCADE, 
    UNIQUE (candidat_id, departement_id) 
);
