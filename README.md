# Gestion des absences — ECF DWWM 2/2

Application web développée dans le cadre de l’ECF DWWM 2/2.

Le projet permet de gérer les stagiaires d’une formation ainsi que leurs absences, leurs justificatifs et plusieurs statistiques associées.

L’application a été réalisée en PHP natif avec une architecture MVC pragmatique, une base de données MySQL et une interface basée sur Bootstrap.

---

## 1. Objectif du projet

L’objectif principal de l’application est de fournir un outil simple permettant :

- de consulter les stagiaires de la formation ;
- de gérer les stagiaires ;
- d’enregistrer et gérer les absences ;
- d’associer un motif à chaque absence ;
- d’ajouter éventuellement un justificatif PDF ;
- de consulter des statistiques sur les absences ;
- d’estimer la perte de revenu liée aux jours d’absence ;
- d’identifier les stagiaires ayant plus de cinq absences sans motif ;
- de réserver les opérations de gestion à un administrateur authentifié.

Le jeu de données utilisé dans le cadre de l’ECF comporte 12 stagiaires.

Les absences sont gérées à la journée entière.

L’application ne gère pas :

- les retards ;
- les demi-journées ;
- les fractions de journée.

---

## 2. Fonctionnalités principales

### Partie publique

Sans authentification, un utilisateur peut notamment :

- consulter le trombinoscope des stagiaires ;
- consulter les statistiques globales ;
- consulter la répartition des absences par motif ;
- consulter le classement des stagiaires selon leur nombre d’absences ;
- consulter l’estimation de la perte de revenu associée aux absences.

Les informations nominatives sensibles liées à la règle des absences sans motif sont réservées à l’administrateur connecté.

### Partie administrateur

Après authentification, l’administrateur peut :

- ajouter un stagiaire ;
- modifier un stagiaire ;
- supprimer un stagiaire lorsque les contraintes de données le permettent ;
- ajouter une photo de stagiaire ;
- consulter les absences ;
- ajouter une absence ;
- modifier une absence ;
- supprimer une absence ;
- sélectionner le stagiaire concerné ;
- sélectionner le motif d’absence ;
- ajouter un justificatif PDF ;
- consulter les justificatifs protégés ;
- se déconnecter.

---

## 3. Motifs d’absence

Les motifs autorisés par l’application sont limités aux quatre valeurs suivantes :

- maladie ;
- sans motif ;
- absence légale ;
- accident du travail.

Les valeurs sont contrôlées côté serveur.

---

## 4. Technologies utilisées

### Back-end

- PHP natif
- Programmation orientée objet
- Architecture MVC
- PDO
- MySQL / MariaDB

### Front-end

- HTML5
- CSS3
- Bootstrap
- JavaScript léger

### Environnement de développement

- WAMP
- Apache
- MySQL
- PHP

Aucun framework PHP ni ORM n’est utilisé.

---

## 5. Architecture

Le projet utilise une architecture MVC volontairement simple afin de conserver une séparation claire des responsabilités.

### Controllers

Les contrôleurs :

- reçoivent les requêtes ;
- vérifient les paramètres ;
- effectuent les validations ;
- orchestrent les appels aux repositories et services ;
- préparent les données nécessaires aux vues ;
- effectuent les redirections.

### Models

Les modèles représentent les principales données métier de l’application.

### Repositories

Les repositories centralisent l’accès à la base de données.

Ils utilisent PDO et des requêtes préparées pour les paramètres provenant des utilisateurs.

### Services

Les services regroupent certaines responsabilités spécifiques, notamment :

- upload des photos ;
- upload des justificatifs PDF ;
- calcul des statistiques.

### Views

Les vues sont responsables de l’affichage HTML.

Les données dynamiques affichées dans l’interface sont échappées afin de limiter les risques XSS.

---

## 6. Structure principale du projet

ECF2/
│
├── app/
│ ├── Controllers/
│ │ ├── AuthController.php
│ │ ├── TraineeController.php
│ │ ├── AbsenceController.php
│ │ └── StatisticsController.php
│ │
│ ├── Core/
│ │ ├── Csrf.php
│ │ ├── Database.php
│ │ └── Router.php
│ │
│ ├── Models/
│ │ ├── AdminModel.php
│ │ ├── TraineeModel.php
│ │ └── AbsenceModel.php
│ │
│ ├── Repositories/
│ │ ├── AdminRepository.php
│ │ ├── TraineeRepository.php
│ │ └── AbsenceRepository.php
│ │
│ ├── Services/
│ │ ├── PhotoUploadService.php
│ │ ├── PdfUploadService.php
│ │ └── StatisticsService.php
│ │
│ └── Views/
│ ├── absences/
│ ├── auth/
│ ├── layouts/
│ ├── statistics/
│ └── trainees/
│
├── config/
│ └── database.php
│
├── public/
│ ├── assets/
│ │ ├── css/
│ │ ├── images/
│ │ └── js/
│ ├── .htaccess
│ └── index.php
│
├── storage/
│ └── uploads/
│ └── justifications/
│
├── ecf2_juan.sql
├── install.php
└── README.md
