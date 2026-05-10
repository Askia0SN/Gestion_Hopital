<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Hopital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= e(url('/tableau_bord/index.php')) ?>">Gestion Hopital</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="navbar-nav">
                <a class="nav-link" href="<?= e(url('/patients/liste.php')) ?>">Patients</a>
                <a class="nav-link" href="<?= e(url('/medecins/liste.php')) ?>">Medecins</a>
                <a class="nav-link" href="<?= e(url('/rendez_vous/liste.php')) ?>">Rendez-vous</a>
                <a class="nav-link" href="<?= e(url('/dossiers_medicaux/liste.php')) ?>">Dossiers medicaux</a>
                <a class="nav-link" href="<?= e(url('/auth/deconnexion.php')) ?>">Deconnexion</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<main class="container pb-5">

