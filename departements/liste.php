<?php
// Inclusion de la connexion à la base de données
require_once '../config/db.php';

// Démarrage de la session pour les messages flash
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Départements - Élections 2025</title>
    
    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation simple -->
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
        <h1>Liste des Départements</h1>
        
        <!-- Bouton pour ajouter un département -->
        <a href="ajouter.php" class="btn btn-success mb-3">Ajouter un département</a>
        
        <!-- Affichage des messages flash -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php 
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>
        
        <!-- Tableau des départements -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom du département</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupération de tous les départements triés par nom
                    $stmt = $pdo->query("SELECT * FROM departements ORDER BY nom ASC");
                    $departements = $stmt->fetchAll();
                    
                    if (count($departements) > 0):
                        foreach ($departements as $departement):
                    ?>
                        <tr>
                            <td><?php echo $departement['id']; ?></td>
                            <td><?php echo htmlspecialchars($departement['nom']); ?></td>
                            <td>
                                <a href="modifier.php?id=<?php echo $departement['id']; ?>" 
                                   class="btn btn-sm btn-warning">Modifier</a>
                                <a href="supprimer.php?id=<?php echo $departement['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucun département trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
