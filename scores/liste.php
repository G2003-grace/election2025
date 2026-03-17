<?php
require_once '../config/db.php';
session_start();

// Récupération des scores avec jointures
$sql = "SELECT s.id, c.nom as candidat_nom, c.prenom as candidat_prenom, 
               d.nom as departement_nom, s.voix, c.photo
        FROM scores s
        JOIN candidats c ON s.candidat_id = c.id
        JOIN departements d ON s.departement_id = d.id
        ORDER BY d.nom, s.voix DESC";
$stmt = $pdo->query($sql);
$scores = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Scores - Élections 2025</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php"> <strong>Élections 2025 - CENA</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../candidats/liste.php">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link" href="../departements/liste.php">Départements</a></li>
                    <li class="nav-item"><a class="nav-link active" href="liste.php">Scores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultats/index.php">Résultats</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Gestion des Scores</h1>
                
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Liste des Scores</h5>
                        <a href="ajouter.php" class="btn btn-primary">Ajouter un Score</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Candidat</th>
                                        <th>Département</th>
                                        <th>Voix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scores as $score): ?>
                                    <tr>
                                        <td>
                                            <?php if ($score['photo']): ?>
                                                <img src="../<?= $score['photo'] ?>" alt="<?= $score['candidat_nom'] ?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <div style="width: 50px; height: 50px; background: #ddd; border-radius: 50%;"></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($score['candidat_prenom'] . ' ' . $score['candidat_nom']) ?></td>
                                        <td><?= htmlspecialchars($score['departement_nom']) ?></td>
                                        <td><strong><?= number_format($score['voix']) ?></strong></td>
                                        <td>
                                            <a href="modifier.php?id=<?= $score['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                            <a href="supprimer.php?id=<?= $score['id'] ?>" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce score ?')">Supprimer</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
