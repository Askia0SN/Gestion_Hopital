<?php
declare(strict_types=1);

require_once __DIR__ . '/config/configuration.php';
require_once __DIR__ . '/config/fonctions.php';

if (isLoggedIn()) {
    rediriger('/tableau_bord/index.php');
}

rediriger('/auth/connexion.php');

