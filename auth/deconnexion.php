<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';

session_destroy();
rediriger('/auth/connexion.php');

