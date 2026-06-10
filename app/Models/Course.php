<?php

namespace App\Models;

use App\Core\Database;

class Course
{
    public static function all(): array
    {
        return Database::connection()->query(
            'SELECT courses.*, users.full_name AS lecturer_name
             FROM courses
             LEFT JOIN users ON users.id = courses.lecturer_id
             ORDER BY courses.code'
        )->fetchAll();
    }

    public static function forLecturer(int $lecturerId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM courses WHERE lecturer_id = ? ORDER BY code');
        $stmt->execute([$lecturerId]);
        return $stmt->fetchAll();
    }

    public static function forStudent(int $studentId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT courses.*, users.full_name AS lecturer_name
             FROM course_enrollments
             JOIN courses ON courses.id = course_enrollments.course_id
             LEFT JOIN users ON users.id = courses.lecturer_id
             WHERE course_enrollments.student_id = ?
             ORDER BY courses.code'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO courses (code, title, lecturer_id, description) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$data['code'], $data['title'], $data['lecturer_id'] ?: null, $data['description'] ?: null]);
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE courses SET code = ?, title = ?, lecturer_id = ?, description = ? WHERE id = ?'
        );
        $stmt->execute([$data['code'], $data['title'], $data['lecturer_id'] ?: null, $data['description'] ?: null, $id]);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM courses WHERE id = ?')->execute([$id]);
    }

    public static function enroll(int $studentId, int $courseId): void
    {
        Database::connection()->prepare(
            'INSERT IGNORE INTO course_enrollments (student_id, course_id) VALUES (?, ?)'
        )->execute([$studentId, $courseId]);
    }

    public static function unenroll(int $studentId, int $courseId): void
    {
        Database::connection()->prepare(
            'DELETE FROM course_enrollments WHERE student_id = ? AND course_id = ?'
        )->execute([$studentId, $courseId]);
    }

    public static function enrollments(): array
    {
        return Database::connection()->query(
            'SELECT course_enrollments.*, students.full_name AS student_name, students.matric_number,
                    courses.code AS course_code, courses.title AS course_title
             FROM course_enrollments
             JOIN users AS students ON students.id = course_enrollments.student_id
             JOIN courses ON courses.id = course_enrollments.course_id
             ORDER BY students.full_name, courses.code'
        )->fetchAll();
    }
}
