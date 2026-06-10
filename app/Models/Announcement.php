<?php

namespace App\Models;

use App\Core\Database;

class Announcement
{
    public static function allForUser(array $user): array
    {
        if ($user['role'] === 'student') {
            $stmt = Database::connection()->prepare(
                'SELECT DISTINCT announcements.*, courses.code AS course_code, users.full_name AS author_name
                 FROM announcements
                 LEFT JOIN courses ON courses.id = announcements.course_id
                 JOIN users ON users.id = announcements.author_id
                 LEFT JOIN course_enrollments ON course_enrollments.course_id = announcements.course_id
                 WHERE announcements.course_id IS NULL OR course_enrollments.student_id = ?
                 ORDER BY announcements.created_at DESC'
            );
            $stmt->execute([(int) $user['id']]);
            return $stmt->fetchAll();
        }

        if ($user['role'] === 'lecturer') {
            $stmt = Database::connection()->prepare(
                'SELECT announcements.*, courses.code AS course_code, users.full_name AS author_name
                 FROM announcements
                 LEFT JOIN courses ON courses.id = announcements.course_id
                 JOIN users ON users.id = announcements.author_id
                 WHERE announcements.author_id = ? OR courses.lecturer_id = ?
                 ORDER BY announcements.created_at DESC'
            );
            $stmt->execute([(int) $user['id'], (int) $user['id']]);
            return $stmt->fetchAll();
        }

        return Database::connection()->query(
            'SELECT announcements.*, courses.code AS course_code, users.full_name AS author_name
             FROM announcements
             LEFT JOIN courses ON courses.id = announcements.course_id
             JOIN users ON users.id = announcements.author_id
             ORDER BY announcements.created_at DESC'
        )->fetchAll();
    }

    public static function create(array $data): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO announcements (author_id, course_id, title, body) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$data['author_id'], $data['course_id'] ?: null, $data['title'], $data['body']]);
    }

    public static function delete(int $id, int $authorId, bool $isAdmin = false): void
    {
        if ($isAdmin) {
            Database::connection()->prepare('DELETE FROM announcements WHERE id = ?')->execute([$id]);
            return;
        }

        Database::connection()
            ->prepare('DELETE FROM announcements WHERE id = ? AND author_id = ?')
            ->execute([$id, $authorId]);
    }
}
