<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function requireRole(string $role, string $loginPath): void
{
    if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: " . $loginPath);
        exit;
    }
}

function escapeHtml($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}
