# Documentation du projet Gestion Hopital

## 1) Presentation du projet

Ce projet est une application web de gestion d'hopital developpee en PHP simple (sans framework) avec MySQL.

Objectif pedagogique:
- manipuler une architecture claire en PHP procedural;
- realiser des CRUD complets;
- gerer l'authentification et les sessions;
- utiliser des requetes preparees PDO.

Fonctionnalites incluses:
- authentification (connexion/deconnexion);
- tableau de bord;
- gestion des patients (liste, ajout, modification, suppression, recherche);
- gestion des medecins (liste, ajout, modification, suppression);
- gestion des rendez-vous (liste, ajout, modification, suppression);
- gestion des dossiers medicaux (liste, ajout, modification, suppression).

---

## 2) Stack technique

- **Backend**: PHP 8+ (compatible XAMPP)
- **Base de donnees**: MySQL / MariaDB
- **Acces DB**: PDO
- **Frontend**: HTML + Bootstrap (CDN)
- **Serveur local**: Apache (XAMPP)

---

## 3) Arborescence du projet

```text
Projet_gestion_Hopital/
├── auth/
│   ├── connexion.php
│   └── deconnexion.php
├── config/
│   ├── configuration.php
│   └── fonctions.php
├── dossiers_medicaux/
│   ├── liste.php
│   ├── formulaire.php
│   └── supprimer.php
├── medecins/
│   ├── liste.php
│   ├── formulaire.php
│   └── supprimer.php
├── patients/
│   ├── liste.php
│   ├── formulaire.php
│   └── supprimer.php
├── rendez_vous/
│   ├── liste.php
│   ├── formulaire.php
│   └── supprimer.php
├── tableau_bord/
│   └── index.php
├── vues/
│   ├── header.php
│   └── footer.php
├── database.sql
├── index.php
└── DOCUMENTATION.md
```

---

## 4) Installation et execution (XAMPP)

### 4.1 Prerequis
- XAMPP installe (Apache + MySQL)
- PHP 8 conseille

### 4.2 Placement du projet
Copier le dossier dans:

`C:/xampp/htdocs/Projet_gestion_Hopital`

### 4.3 Import de la base
1. Demarrer Apache et MySQL dans XAMPP.
2. Ouvrir phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Importer le fichier `database.sql`.

Le script cree:
- la base `gestion_hopital`;
- les tables;
- un compte admin par defaut.

### 4.4 Configuration
Verifier le fichier `config/configuration.php`:
- `BASE_URL` doit correspondre au nom du dossier dans `htdocs`
- acces MySQL: host, user, password, database

Valeur actuelle:
- `BASE_URL = '/Projet_gestion_Hopital'`

### 4.5 Lancement
Ouvrir:

[http://localhost/Projet_gestion_Hopital/](http://localhost/Projet_gestion_Hopital/)

Compte de test:
- Email: `admin@hopital.local`
- Mot de passe: `admin123`

---

## 5) Structure de la base de donnees

### 5.1 Table `users`
Colonnes principales:
- `id`
- `name`
- `email` (unique)
- `password` (hash)
- `role` (`admin`, `secretaire`, `medecin`)

Utilisation:
- authentification des utilisateurs.

### 5.2 Table `patients`
Colonnes principales:
- `first_name`, `last_name`
- `birth_date`
- `gender`
- `phone`, `address`

Utilisation:
- informations administratives du patient.

### 5.3 Table `doctors`
Colonnes principales:
- `name`
- `specialty`
- `phone`
- `email`

Utilisation:
- gestion du personnel medical.

### 5.4 Table `appointments`
Colonnes principales:
- `patient_id` (FK vers `patients`)
- `doctor_id` (FK vers `doctors`)
- `appointment_date`, `appointment_time`
- `status` (`en_attente`, `confirme`, `termine`)

Utilisation:
- planification des consultations.

### 5.5 Table `medical_records`
Colonnes principales:
- `patient_id` (FK vers `patients`)
- `doctor_id` (FK vers `doctors`)
- `consultation_date`
- `diagnosis`, `treatment`, `notes`

Utilisation:
- suivi des consultations medicales.

### 5.6 Regles de suppression
Les FK sur `appointments` et `medical_records` sont en `ON DELETE CASCADE`:
- suppression d'un patient => suppression de ses rendez-vous et dossiers;
- suppression d'un medecin => suppression de ses rendez-vous et dossiers.

---

## 6) Description des modules

## 6.1 Module Authentification (`auth`)
- `connexion.php`: formulaire de login + verification email/mot de passe.
- `deconnexion.php`: destruction de session.

Flux:
1. l'utilisateur saisit ses identifiants;
2. verification en DB avec PDO;
3. `password_verify` compare le mot de passe;
4. stockage de `user_id` et `user_name` en session;
5. redirection vers le tableau de bord.

## 6.2 Module Tableau de bord (`tableau_bord`)
- `index.php`:
  - compte le nombre de patients et medecins;
  - affiche les rendez-vous du jour.

## 6.3 Module Patients (`patients`)
- `liste.php`: affichage + recherche.
- `formulaire.php`: creation/modification.
- `supprimer.php`: suppression.

## 6.4 Module Medecins (`medecins`)
- `liste.php`: affichage.
- `formulaire.php`: creation/modification.
- `supprimer.php`: suppression.

## 6.5 Module Rendez-vous (`rendez_vous`)
- `liste.php`: affichage avec jointures patient/medecin.
- `formulaire.php`: creation/modification (date, heure, statut).
- `supprimer.php`: suppression.

## 6.6 Module Dossiers medicaux (`dossiers_medicaux`)
- `liste.php`: affichage des informations medicales.
- `formulaire.php`: creation/modification.
- `supprimer.php`: suppression.

## 6.7 Composants partages
- `config/configuration.php`: connexion PDO + session + `BASE_URL`.
- `config/fonctions.php`:
  - `url()`, `rediriger()`
  - `isLoggedIn()`, `requireLogin()`
  - `e()` pour echappement HTML.
- `vues/header.php` et `vues/footer.php`: layout commun.

---

## 7) Routes principales

- `/index.php` -> redirige vers connexion ou dashboard selon session.
- `/auth/connexion.php`
- `/auth/deconnexion.php`
- `/tableau_bord/index.php`
- `/patients/liste.php`
- `/medecins/liste.php`
- `/rendez_vous/liste.php`
- `/dossiers_medicaux/liste.php`

---

## 8) Securite appliquee

- utilisation de **PDO + requetes preparees** pour limiter l'injection SQL;
- `password_verify()` pour verifier le mot de passe hash;
- protection XSS de base avec la fonction `e()` (htmlspecialchars);
- controle de session via `requireLogin()` avant acces aux modules proteges.

Limites actuelles (ameliorations possibles):
- pas de token CSRF;
- pas de gestion avancee des roles (admin/secretaire/medecin);
- validations serveur basiques.

---

## 9) Procedure de test fonctionnel

1. Se connecter avec le compte admin.
2. Ajouter 2 patients.
3. Ajouter 2 medecins.
4. Creer un rendez-vous.
5. Creer un dossier medical.
6. Modifier chaque element.
7. Supprimer un patient et verifier la suppression cascade.
8. Se deconnecter.

Resultat attendu:
- toutes les operations CRUD fonctionnent;
- navigation stable;
- pas d'erreur SQL.

---

## 10) Depannage

### Erreur "Page introuvable"
- verifier `BASE_URL` dans `config/configuration.php`.

### Erreur connexion DB
- verifier MySQL demarre;
- verifier identifiants DB (`root`, mot de passe).

### Login impossible
- verifier import de `database.sql`;
- verifier presence de l'utilisateur `admin@hopital.local`.

### Caracteres speciaux mal affiches
- verifier `charset=utf8mb4` dans PDO;
- verifier encodage UTF-8 des fichiers.

---

## 11) Evolutions futures suggerees

- gestion des roles et permissions;
- pagination des listes;
- validation metier plus stricte;
- CSRF token sur formulaires;
- export PDF;
- journal d'activite;
- architecture MVC plus stricte.

