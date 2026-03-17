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

// Récupération du score
$stmt = $pdo->prepare("SELECT * FROM scores WHERE id = ?");
$stmt->execute([$id]);
$score = $stmt->fetch();
if (!$score) {
    $_SESSION['message'] = "Score introuvable.";
    header('Location: liste.php');
    exit;
}

// Récupération des candidats et départements
$candidats = $pdo->query("SELECT id, nom, prenom FROM candidats ORDER BY nom")->fetchAll();
$departements = $pdo->query("SELECT id, nom FROM departements ORDER BY nom")->fetchAll();

// Correction de la vérification POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidat_id = $_POST['candidat_id'] ?? null;
    $departement_id = $_POST['departement_id'] ?? null;
    $voix = $_POST['voix'] ?? null;

    if ($candidat_id && $departement_id && is_numeric($voix) && $voix >= 0) {
        // Vérifier si un autre score existe déjà pour ce candidat et département
        $stmt = $pdo->prepare("SELECT id FROM scores WHERE candidat_id = ? AND departement_id = ? AND id != ?");
        $stmt->execute([$candidat_id, $departement_id, $id]);
        if ($stmt->fetch()) {
            $_SESSION['message'] = "Un score existe déjà pour ce candidat dans ce département.";
        } else {
            // Mettre à jour le score
            $stmt = $pdo->prepare("UPDATE scores SET candidat_id = ?, departement_id = ?, voix = ? WHERE id = ?");
            $stmt->execute([$candidat_id, $departement_id, $voix, $id]);
            $_SESSION['message'] = "Score modifié avec succès.";
            header('Location: liste.php');
            exit;
        }
    } else {
        $_SESSION['message'] = "Veuillez remplir tous les champs correctement.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modifier un Score - Élections 2025</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
 <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php"> <strong>Élections 2025 - CENA</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../candidats/liste.php">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link" href="../departements/liste.php">Départements</a></li>
                    <li class="nav-item"><a class="nav-link" href="../scores/liste.php">Scores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultats/index.php">Résultats</a></li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container mt-4">
    <h1>Modifier un Score</h1>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <form method="post" action="modifier.php?id=<?= $id ?>">
        <div class="mb-3">
            <label for="candidat_id" class="form-label">Candidat</label>
            <select name="candidat_id" id="candidat_id" class="form-select" required>
                <?php foreach ($candidats as $candidat): ?>
                    <option value="<?= $candidat['id'] ?>" <?= $candidat['id'] == $score['candidat_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($candidat['prenom'] . ' ' . $candidat['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="departement_id" class="form-label">Département</label>
            <select name="departement_id" id="departement_id" class="form-select" required>
                <?php foreach ($departements as $departement): ?>
                    <option value="<?= $departement['id'] ?>" <?= $departement['id'] == $score['departement_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($departement['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="voix" class="form-label">Nombre de voix</label>
            <input type="number" name="voix" id="voix" class="form-control" value="<?= $score['voix'] ?>" min="0" required />
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="liste.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
