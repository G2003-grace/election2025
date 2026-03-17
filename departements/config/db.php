<?php
// Configuration de la connexion à la base de données
// Ces informations peuvent être modifiées selon votre environnement

$host = 'localhost';      // Adresse du serveur MySQL
$dbname = 'election'; // Nom de la base de données
$username = 'root';       // Nom d'utilisateur MySQL (par défaut sur WAMP)
$password = '';           // Mot de passe MySQL (par défaut sur WAMP)

try {
    // Création de la connexion PDO
    // PDO est une extension PHP qui permet d'accéder à différentes bases de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuration des options PDO pour afficher les erreurs en mode développement
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Définir le mode de récupération des données par défaut
    // FETCH_ASSOC retourne les résultats sous forme de tableau associatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // En cas d'erreur de connexion, afficher le message d'erreur
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>
