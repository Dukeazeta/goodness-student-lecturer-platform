<?php

namespace App\Models;

use App\Core\Database;

class Message
{
    public static function forStudent(int $studentId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT messages.*, users.full_name AS lecturer_name, courses.code AS course_code
             FROM messages
             JOIN users ON users.id = messages.lecturer_id
             LEFT JOIN courses ON courses.id = messages.course_id
             WHERE messages.student_id = ?
             ORDER BY messages.created_at DESC'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public static function forLecturer(int $lecturerId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT messages.*, users.full_name AS student_name, users.email AS student_email, courses.code AS course_code
             FROM messages
             JOIN users ON users.id = messages.student_id
             LEFT JOIN courses ON courses.id = messages.course_id
             WHERE messages.lecturer_id = ?
             ORDER BY messages.created_at DESC'
        );
        $stmt->execute([$lecturerId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO messages (student_id, lecturer_id, course_id, subject, body, status)
             VALUES (?, ?, ?, ?, ?, "open")'
        );
        $stmt->execute([
            $data['student_id'],
            $data['lecturer_id'],
            $data['course_id'] ?: null,
            $data['subject'],
            $data['body'],
        ]);
    }

    public static function reply(int $id, int $lecturerId, string $reply): void
    {
        Database::connection()->prepare(
            'UPDATE messages
             SET reply = ?, status = "answered", replied_at = NOW()
             WHERE id = ? AND lecturer_id = ?'
        )->execute([$reply, $id, $lecturerId]);
    }
}
