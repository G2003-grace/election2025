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

// Récupération du département
$stmt = $pdo->prepare("SELECT * FROM departements WHERE id = ?");
$stmt->execute([$id]);
$departement = $stmt->fetch();

if (!$departement) {
    $_SESSION['message'] = "Département non trouvé.";
    $_SESSION['message_type'] = "danger";
    header('Location: liste.php');
    exit;
}

// Traitement du formulaire
$errors = [];
$nom = $departement['nom'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }
    
    // Vérification si le nom existe déjà (sauf pour le département actuel)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM departements WHERE nom = ? AND id != ?");
    $stmt->execute([$nom, $id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "Ce département existe déjà.";
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE departements SET nom = :nom WHERE id = :id");
        $stmt->execute([':nom' => $nom, ':id' => $id]);
        
        $_SESSION['message'] = "Département modifié avec succès.";
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
    <title>Modifier un Département - Élections 2025</title>
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
        <h1>Modifier le Département</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="modifier.php?id=<?php echo $id; ?>" method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du département *</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($nom); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
