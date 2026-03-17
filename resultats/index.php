<?php
// Connexion à la base de données
require_once '../config/db.php';

// Récupération des résultats détaillés avec tri
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'departement';
$ordre = isset($_GET['ordre']) ? $_GET['ordre'] : 'ASC';

// Construction de la requête selon le tri demandé
switch($tri) {
    case 'candidat':
        $sql_detail = "SELECT d.nom as departement, CONCAT(c.prenom, ' ', c.nom) as candidat, s.voix as score 
                       FROM scores s
                       INNER JOIN candidats c ON s.candidat_id = c.id
                       INNER JOIN departements d ON s.departement_id = d.id
                       ORDER BY candidat $ordre, d.nom";
        break;
    case 'score':
        $sql_detail = "SELECT d.nom as departement, CONCAT(c.prenom, ' ', c.nom) as candidat, s.voix as score 
                       FROM scores s
                       INNER JOIN candidats c ON s.candidat_id = c.id
                       INNER JOIN departements d ON s.departement_id = d.id
                       ORDER BY score $ordre";
        break;
    default:
        $sql_detail = "SELECT d.nom as departement, CONCAT(c.prenom, ' ', c.nom) as candidat, s.voix as score 
                       FROM scores s
                       INNER JOIN candidats c ON s.candidat_id = c.id
                       INNER JOIN departements d ON s.departement_id = d.id
                       ORDER BY departement $ordre, score DESC";
}

$resultat_detail = $pdo->query($sql_detail);
$scores_detail = $resultat_detail->fetchAll(PDO::FETCH_ASSOC);

// Calcul des totaux nationaux
$sql_total = "SELECT CONCAT(c.prenom, ' ', c.nom) as candidat, SUM(s.voix) as total_national
              FROM scores s
              INNER JOIN candidats c ON s.candidat_id = c.id
              GROUP BY c.id, c.prenom, c.nom
              ORDER BY total_national DESC";

$resultat_total = $pdo->query($sql_total);
$totaux_nationaux = $resultat_total->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total général
$total_general = array_sum(array_column($totaux_nationaux, 'total_national'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats Élections 2025</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php"> <strong>Élections 2025 - CENA</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../candidats/liste.php">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link" href="../departements/liste.php">Départements</a></li>
                    <li class="nav-item"><a class="nav-link" href="../scores/liste.php">Scores</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php">Résultats</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Résultats des élections 2025</h1>
        
        <!-- Carte de statistiques -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total des voix</h5>
                        <h3 class="text-primary"><?php echo number_format($total_general); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Candidats</h5>
                        <h3 class="text-success"><?php echo count($totaux_nationaux); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Départements</h5>
                        <h3 class="text-info"><?php echo count(array_unique(array_column($scores_detail, 'departement'))); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section détail des scores -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Détail des scores par département</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <a href="?tri=departement&ordre=<?php echo $tri == 'departement' && $ordre == 'ASC' ? 'DESC' : 'ASC'; ?>" 
                                       class="text-decoration-none text-dark">
                                        Département <?php echo $tri == 'departement' ? ($ordre == 'ASC' ? '↑' : '↓') : ''; ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?tri=candidat&ordre=<?php echo $tri == 'candidat' && $ordre == 'ASC' ? 'DESC' : 'ASC'; ?>" 
                                       class="text-decoration-none text-dark">
                                        Candidat <?php echo $tri == 'candidat' ? ($ordre == 'ASC' ? '↑' : '↓') : ''; ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?tri=score&ordre=<?php echo $tri == 'score' && $ordre == 'ASC' ? 'DESC' : 'ASC'; ?>" 
                                       class="text-decoration-none text-dark">
                                        Score <?php echo $tri == 'score' ? ($ordre == 'ASC' ? '↑' : '↓') : ''; ?>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($scores_detail as $ligne): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ligne['departement']); ?></td>
                                <td><?php echo htmlspecialchars($ligne['candidat']); ?></td>
                                <td><?php echo number_format($ligne['score']); ?> voix</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section résultats nationaux -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Résultat national par candidat</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Candidat</th>
                                <th>Total des voix (national)</th>
                                <th>Pourcentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($totaux_nationaux as $total): ?>
                            <?php $pourcentage = $total_general > 0 ? ($total['total_national'] / $total_general) * 100 : 0; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($total['candidat']); ?></td>
                                <td><?php echo number_format($total['total_national']); ?> voix</td>
                                <td><?php echo number_format($pourcentage, 2); ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
