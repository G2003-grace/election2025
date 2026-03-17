<?php
// Inclusion de la connexion à la base de données
require_once '../config/db.php';

// Démarrage de la session pour les messages flash
session_start();

// Vérification de l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "ID invalide.";
    $_SESSION['message_type'] = "danger";
    header('Location: liste.php');
    exit;
}

$id = (int)$_GET['id'];

// La confirmation est gérée par JavaScript dans liste.php

// Vérification si le département existe
$stmt = $pdo->prepare("SELECT id FROM departements WHERE id = ?");
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    $_SESSION['message'] = "Département non trouvé.";
    $_SESSION['message_type'] = "danger";
    header('Location: liste.php');
    exit;
}

// Vérification si des scores sont associés à ce département
$stmt = $pdo->prepare("SELECT COUNT(*) FROM scores WHERE departement_id = ?");
$stmt->execute([$id]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['message'] = "Impossible de supprimer ce département car des scores y sont associés.";
    $_SESSION['message_type'] = "warning";
    header('Location: liste.php');
    exit;
}

// Suppression du département
$stmt = $pdo->prepare("DELETE FROM departements WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['message'] = "Département supprimé avec succès.";
$_SESSION['message_type'] = "success";
header('Location: liste.php');
exit;
?>
