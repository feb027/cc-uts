<?php
namespace App\Core;

function view(string $template, array $data = []): void
{
    extract($data);
    $templatePath = __DIR__ . '/../Views/' . $template . '.php';
    if (!file_exists($templatePath)) {
        http_response_code(500);
        echo "View {$template} not found";
        return;
    }
    require $templatePath;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function flash(string $key, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function asset(string $path): string
{
    return '/public/' . ltrim($path, '/');
}
