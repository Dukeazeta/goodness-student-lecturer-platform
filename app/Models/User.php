<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function all(?string $role = null): array
    {
        if ($role) {
            $stmt = Database::connection()->prepare('SELECT * FROM users WHERE role = ? ORDER BY full_name');
            $stmt->execute([$role]);
            return $stmt->fetchAll();
        }

        return Database::connection()
            ->query('SELECT * FROM users ORDER BY FIELD(role, "admin", "lecturer", "student"), full_name')
            ->fetchAll();
    }

    public static function create(array $data): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users (full_name, email, password_hash, role, matric_number, department, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'],
            $data['matric_number'] ?: null,
            $data['department'] ?: null,
            $data['status'] ?? 'active',
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $fields = [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'matric_number' => $data['matric_number'] ?: null,
            'department' => $data['department'] ?: null,
            'status' => $data['status'],
        ];

        $sql = 'UPDATE users SET full_name = ?, email = ?, role = ?, matric_number = ?, department = ?, status = ?';
        $values = array_values($fields);

        if (!empty($data['password'])) {
            $sql .= ', password_hash = ?';
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = ?';
        $values[] = $id;
        Database::connection()->prepare($sql)->execute($values);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
    }

    public static function counts(): array
    {
        $rows = Database::connection()
            ->query('SELECT role, COUNT(*) AS total FROM users GROUP BY role')
            ->fetchAll(PDO::FETCH_KEY_PAIR);

        return [
            'admin' => (int) ($rows['admin'] ?? 0),
            'lecturer' => (int) ($rows['lecturer'] ?? 0),
            'student' => (int) ($rows['student'] ?? 0),
        ];
    }
}
