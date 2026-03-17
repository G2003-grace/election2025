<?php
// Inclusion de la connexion à la base de données
require_once '../config/db.php';

// Démarrage de la session pour les messages flash
session_start();

// Initialisation des variables
$errors = [];
$nom = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    
    // Validation simple
    if (empty($nom)) {
        $errors[] = "Le nom du département est obligatoire.";
    }
    
    // Vérification si le département existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM departements WHERE nom = ?");
    $stmt->execute([$nom]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "Ce département existe déjà.";
    }
    
    // Si pas d'erreurs, insertion en base
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO departements (nom) VALUES (:nom)");
        $stmt->execute([':nom' => $nom]);
        
        $_SESSION['message'] = "Département ajouté avec succès.";
        $_SESSION['message_type'] = "success";
        header('Location: liste.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Département - Élections 2025</title>
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
                    <li class="nav-item"><a class="nav-link" href="../scores/liste.php">Scores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultats/index.php">Résultats</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-4">
        <h1>Ajouter un Département</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="ajouter.php" method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du département *</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($nom); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
