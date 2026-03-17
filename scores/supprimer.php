<?php
require_once '../config/db.php';
session_start();

// Vérification de l'ID du score
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    $_SESSION['message'] = "ID de score invalide.";
    header('Location: liste.php');
    exit;
}

// Suppression du score
$stmt = $pdo->prepare("DELETE FROM scores WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['message'] = "Score supprimé avec succès.";
header('Location: liste.php');
exit;
?>
