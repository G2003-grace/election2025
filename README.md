# Élections 2025 - Système de Gestion des Élections Présidentielles

## Description du Projet
Système web complet de gestion des élections présidentielles 2025, développé en PHP procédural avec MySQL et Bootstrap 5.

## Fonctionnalités
- **Gestion des candidats** : CRUD complet avec upload de photos
- **Gestion des départements** : CRUD complet
- **Gestion des scores** : Enregistrement des résultats par département
- **Affichage des résultats** : Tableaux détaillés et cumulés

## Structure du Projet
```
/election2025/
├── index.php                 # Page d'accueil
├── assets/
│   └── css/
│       └── bootstrap.min.css
├── candidats/
│   ├── liste.php            # Liste des candidats
│   ├── ajouter.php          # Ajouter un candidat
│   ├── modifier.php         # Modifier un candidat
│   └── supprimer.php        # Supprimer un candidat
├── departements/
│   ├── liste.php            # Liste des départements
│   ├── ajouter.php          # Ajouter un département
│   ├── modifier.php         # Modifier un département
│   └── supprimer.php        # Supprimer un département
├── scores/
│   ├── liste.php            # Liste des scores
│   ├── ajouter.php          # Ajouter un score
│   ├── modifier.php         # Modifier un score
│   └── supprimer.php        # Supprimer un score
├── resultats/
│   ├── index.php            # Affichage des résultats
├── uploads/
│   ├── .htaccess            # Sécurisation du dossier
├── config/
│   └── db.php               # Connexion à la base de données
└── README.md                # Documentation du projet
```

## Installation et Configuration

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)

### Étapes d'installation
1. Clonez le projet ou téléchargez les fichiers
2. Importez le fichier election.sql dans votre base de données
3. Configurez la connexion dans config/db.php
4. Lancez votre serveur web

### Configuration de la base de données
```sql
CREATE DATABASE election2025;
USE election2025;
-- Importez le fichier election.sql
```

### Configuration de la connexion
```php
// config/db.php
$host = 'localhost';
$dbname = 'election2025';
$username = 'root';
$password = '';
```

## Utilisation

### Gestion des Candidats
- **Ajouter un candidat** : candidats/ajouter.php
- **Modifier un candidat** : candidats/modifier.php
- **Supprimer un candidat** : candidats/supprimer.php

### Gestion des Départements
- **Ajouter un département** : departements/ajouter.php
- **Modifier un département** : departements/modifier.php
- **Supprimer un département** : departements/supprimer.php

### Gestion des Scores
- **Ajouter un score** : scores/ajouter.php
- **Modifier un score** : scores/modifier.php
- **Supprimer un score** : scores/supprimer.php

## Structure de la Base de Données

### Tables principales
- **candidats**: Informations des candidats
- **departements**: Liste des départements
- **scores**: Résultats par département

### Relations
- scores.candidat_id → candidats.id
- scores.departement_id → departements.id

## Fonctionnalités Techniques

### Sécurité
- Validation des entrées utilisateur
- Upload sécurisé des fichiers
- Protection contre les injections SQL
- Sécurisation du dossier uploads avec .htaccess

### Sécurité des fichiers
- Extensions autorisées : JPG, JPEG, PNG
- Taille maximale : 2 Mo
- Renommage automatique des fichiers uploadés

## Documentation des Modules

### Module Candidats
- **Fonctionnalités** : CRUD complet avec upload de photos
- **Fonctionnalités** : Gestion des candidats avec leurs informations
- **Fonctions clés** : upload_photo(), valider_formulaire()

### Module Départements
- **Fonctionnalités** : CRUD complet
- **Fonctionnalités** : Gestion simple des départements
- **Fonctions clés** : valider_nom()

### Module Scores
- **Fonctionnalités** : Enregistrement des résultats
- **Fonctionnalités** : Gestion des scores par département
- **Fonctions clés** : valider_score()

## Utilisation du Système

### Pour les administrateurs
- Gestion complète des candidats, départements et scores
- Interface simple et intuitive
- Messages de confirmation et d'erreur clairs

### Pour les utilisateurs
- Interface simple et intuitive
- Messages de confirmation et d'erreur clairs

## Support et Maintenance
- Code simple et bien commenté
- Documentation complète
- Messages d'erreur clairs

## Conclusion
Ce système offre une solution complète et simple pour la gestion des élections présidentielles, avec une interface intuitive et un code bien documenté.
