<?php
require_once '../config/db.php';

session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Élections Présidentielle 2025</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <strong>Élections 2025 - CENA</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="candidats/liste.php">Candidats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="departements/liste.php">Départements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="scores/liste.php">Scores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="resultats/index.php">Résultats</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Bienvenue dans le Système de Gestion des Élections</h1>
                <p class="text-center text-muted">Application de gestion des candidats, départements et résultats des élections présidentielles 2025</p>
            </div>
        </div>

        <!-- Cartes de navigation -->
        <div class="row mt-5">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Candidats</h5>
                        <p class="card-text">Gérer les candidats à l'élection</p>
                        <a href="candidats/liste.php" class="btn btn-primary">Accéder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Départements</h5>
                        <p class="card-text">Gérer les départements du Bénin</p>
                        <a href="departements/liste.php" class="btn btn-success">Accéder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Scores</h5>
                        <p class="card-text">Enregistrer les scores par département</p>
                        <a href="scores/liste.php" class="btn btn-warning">Accéder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Résultats</h5>
                        <p class="card-text">Voir les résultats détaillés</p>
                        <a href="resultats/index.php" class="btn btn-info">Accéder</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mt-4">
            <div class="col-12">
                <h3 class="text-center mb-4">Statistiques du système</h3>
            </div>
            
            <?php
            // Récupération des statistiques depuis la base de données
            
            // Nombre total de candidats
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM candidats");
            $totalCandidats = $stmt->fetch()['total'];
            
            // Nombre total de départements
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM departements");
            $totalDepartements = $stmt->fetch()['total'];
            
            // Nombre total de scores enregistrés
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM scores");
            $totalScores = $stmt->fetch()['total'];
            ?>
            
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4><?php echo $totalCandidats; ?></h4>
                        <p>Candidats inscrits</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4><?php echo $totalDepartements; ?></h4>
                        <p>Départements</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4><?php echo $totalScores; ?></h4>
                        <p>Scores enregistrés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclusion de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
