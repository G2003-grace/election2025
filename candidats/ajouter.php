<?php
// Inclusion de la connexion à la base de données
require_once '../config/db.php';

// Démarrage de la session pour les messages flash
session_start();

// Initialisation des variables pour les erreurs et les valeurs du formulaire
$errors = [];
$nom = $prenom = $date_naissance = $parti_politique = "";

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $parti_politique = trim($_POST['parti_politique']);
    
    // Validation simple des champs obligatoires
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }
    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire.";
    }
    if (empty($date_naissance)) {
        $errors[] = "La date de naissance est obligatoire.";
    }
    if (empty($parti_politique)) {
        $errors[] = "Le parti politique est obligatoire.";
    }
    
    // Gestion de l'upload de la photo
    $photoNomFichier = null; // Nom du fichier à enregistrer en base
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $photo = $_FILES['photo'];
        
        // Vérification des erreurs d'upload
        if ($photo['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors de l'upload de la photo.";
        } else {
            // Vérification de la taille (max 2 Mo)
            if ($photo['size'] > 2 * 1024 * 1024) {
                $errors[] = "La photo ne doit pas dépasser 2 Mo.";
            }
            
            // Vérification de l'extension autorisée
            $extensionsAutorisees = ['jpg', 'jpeg', 'png'];
            $extensionFichier = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
            if (!in_array($extensionFichier, $extensionsAutorisees)) {
                $errors[] = "Format de photo non autorisé. Seuls JPG, JPEG et PNG sont acceptés.";
            }
        }
    }
    
    // Si pas d'erreurs, on peut enregistrer le candidat
    if (empty($errors)) {
        // Si photo uploadée, on la déplace dans /uploads/ avec un nom unique
        if (isset($photo) && $photo['error'] === UPLOAD_ERR_OK) {
            $nomUnique = uniqid() . '.' . $extensionFichier;
            $cheminDestination = '../uploads/' . $nomUnique;
            if (!move_uploaded_file($photo['tmp_name'], $cheminDestination)) {
                $errors[] = "Erreur lors de l'enregistrement de la photo.";
            } else {
                $photoNomFichier = $nomUnique;
            }
        }
        
        // Insertion en base de données
        if (empty($errors)) {
            $sql = "INSERT INTO candidats (nom, prenom, date_naissance, parti_politique, photo) VALUES (:nom, :prenom, :date_naissance, :parti_politique, :photo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':date_naissance' => $date_naissance,
                ':parti_politique' => $parti_politique,
                ':photo' => $photoNomFichier
            ]);
            
            // Message de succès et redirection vers la liste
            $_SESSION['message'] = "Candidat ajouté avec succès.";
            $_SESSION['message_type'] = "success";
            header('Location: liste.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Candidat - Élections 2025</title>
    
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
                    <li class="nav-item"><a class="nav-link active" href="liste.php">Scores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultats/index.php">Résultats</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Ajouter un Candidat</h1>
        
        <!-- Affichage des erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Formulaire d'ajout -->
        <form action="ajouter.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom *</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($nom); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom *</label>
                <input type="text" name="prenom" id="prenom" class="form-control" value="<?php echo htmlspecialchars($prenom); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="date_naissance" class="form-label">Date de naissance *</label>
                <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="<?php echo htmlspecialchars($date_naissance); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="parti_politique" class="form-label">Parti politique *</label>
                <input type="text" name="parti_politique" id="parti_politique" class="form-control" value="<?php echo htmlspecialchars($parti_politique); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="photo" class="form-label">Photo (JPG, JPEG, PNG, max 2 Mo)</label>
                <input type="file" name="photo" id="photo" class="form-control" accept=".jpg,.jpeg,.png">
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
