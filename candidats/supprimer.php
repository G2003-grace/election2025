<?php
// Inclusion de la connexion à la base de données
require_once '../config/db.php';

// Démarrage de la session pour les messages flash
session_start();

// Vérification que l'ID du candidat est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "ID de candidat invalide.";
    $_SESSION['message_type'] = "danger";
    header('Location: liste.php');
    exit;
}

$id = (int)$_GET['id'];

// La confirmation est gérée par JavaScript dans liste.php

// Récupération des informations du candidat pour obtenir le nom de la photo
$stmt = $pdo->prepare("SELECT id, nom, prenom, photo FROM candidats WHERE id = ?");
$stmt->execute([$id]);
$candidat = $stmt->fetch();

if (!$candidat) {
    $_SESSION['message'] = "Candidat non trouvé.";
    $_SESSION['message_type'] = "danger";
    header('Location: liste.php');
    exit;
}

// Suppression du candidat et de sa photo
try {
    // Commencer une transaction
    $pdo->beginTransaction();
    
    // Supprimer le candidat de la base de données
    $stmt = $pdo->prepare("DELETE FROM candidats WHERE id = ?");
    $stmt->execute([$id]);
    
    // Supprimer la photo du serveur si elle existe
    if ($candidat['photo'] && file_exists('../uploads/' . $candidat['photo'])) {
        unlink('../uploads/' . $candidat['photo']);
    }
    
    // Valider la transaction
    $pdo->commit();
    
    $_SESSION['message'] = "Candidat supprimé avec succès.";
    $_SESSION['message_type'] = "success";
    
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    $_SESSION['message'] = "Erreur lors de la suppression : " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

// Redirection vers la liste
header('Location: liste.php');
exit;
?>
