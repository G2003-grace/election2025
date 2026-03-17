<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
require_once '../config/db.php';

// Démarrage de la session pour gérer les messages flash
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Candidats - Élections 2025</title>
    
    <!-- Inclusion de Bootstrap -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation simple -->
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
        <h1>Liste des Candidats</h1>
        
        <!-- Bouton pour ajouter un nouveau candidat -->
        <a href="ajouter.php" class="btn btn-success mb-3">Ajouter un candidat</a>
        
        <!-- Affichage des messages flash -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php 
            // Suppression du message après affichage
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>
        
        <!-- Tableau des candidats -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Parti politique</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupération de tous les candidats triés par nom
                    $stmt = $pdo->query("SELECT * FROM candidats ORDER BY nom ASC");
                    $candidats = $stmt->fetchAll();
                    
                    // Vérification s'il y a des candidats
                    if (count($candidats) > 0):
                        foreach ($candidats as $candidat):
                    ?>
                        <tr>
                            <td>
                                <?php if ($candidat['photo']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($candidat['photo']); ?>" 
                                         alt="Photo de <?php echo htmlspecialchars($candidat['prenom']); ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50x50" 
                                         alt="Pas de photo" 
                                         style="width: 50px; height: 50px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($candidat['nom']); ?></td>
                            <td><?php echo htmlspecialchars($candidat['prenom']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($candidat['date_naissance'])); ?></td>
                            <td><?php echo htmlspecialchars($candidat['parti_politique']); ?></td>
                            <td>
                                <!-- Boutons modifier et supprimer -->
                                <a href="modifier.php?id=<?php echo $candidat['id']; ?>" 
                                   class="btn btn-sm btn-warning">Modifier</a>
                                <a href="supprimer.php?id=<?php echo $candidat['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="6" class="text-center">Aucun candidat trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inclusion de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
