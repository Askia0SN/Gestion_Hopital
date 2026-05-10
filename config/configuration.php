<?php
declare(strict_types=1);

const BASE_URL = '/Projet_gestion_Hopital';

$dbHost = '127.0.0.1';
$dbName = 'gestion_hopital';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion a la base de donnees : ' . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

