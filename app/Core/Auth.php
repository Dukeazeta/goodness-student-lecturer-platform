<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function user(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return User::find((int) $_SESSION['user_id']);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if (!$user || $user['status'] !== 'active') {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }

    public static function requireLogin(): array
    {
        $user = self::user();
        if (!$user) {
            \redirect('login');
        }
        return $user;
    }

    public static function requireRole(array $roles): array
    {
        $user = self::requireLogin();
        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            exit('You do not have permission to access this page.');
        }
        return $user;
    }
}
