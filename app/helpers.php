<?php

function base_path(string $path = ''): string
{
    $root = dirname(__DIR__);
    return $path ? $root . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path) : $root;
}

function public_path(string $path = ''): string
{
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public';
    return $path ? $root . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path) : $root;
}

function url(string $route = '', array $params = []): string
{
    $query = array_merge(['route' => $route], $params);
    if ($route === '') {
        unset($query['route']);
    }
    return 'index.php' . ($query ? '?' . http_build_query($query) : '');
}

function asset(string $path): string
{
    return 'assets/' . ltrim($path, '/');
}

function redirect(string $route, array $params = []): void
{
    header('Location: ' . url($route, $params));
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Invalid form token. Please go back and try again.');
    }
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}
