<?php
declare(strict_types=1);

function url(string $path): string
{
    return BASE_URL . $path;
}

function rediriger(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        rediriger('/auth/connexion.php');
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

