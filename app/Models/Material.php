<?php

namespace App\Models;

use App\Core\Database;

class Material
{
    public static function all(): array
    {
        return Database::connection()->query(
            'SELECT course_materials.*, courses.code AS course_code, courses.title AS course_title,
                    users.full_name AS uploader_name
             FROM course_materials
             JOIN courses ON courses.id = course_materials.course_id
             JOIN users ON users.id = course_materials.uploader_id
             ORDER BY course_materials.created_at DESC'
        )->fetchAll();
    }

    public static function forLecturer(int $lecturerId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT course_materials.*, courses.code AS course_code, courses.title AS course_title,
                    users.full_name AS uploader_name
             FROM course_materials
             JOIN courses ON courses.id = course_materials.course_id
             JOIN users ON users.id = course_materials.uploader_id
             WHERE courses.lecturer_id = ?
             ORDER BY course_materials.created_at DESC'
        );
        $stmt->execute([$lecturerId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO course_materials
                (course_id, uploader_id, title, description, searchable_text, file_path, file_name, file_type, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['course_id'],
            $data['uploader_id'],
            $data['title'],
            $data['description'] ?: null,
            $data['searchable_text'],
            $data['file_path'] ?: null,
            $data['file_name'] ?: null,
            $data['file_type'] ?: null,
            isset($data['is_active']) ? 1 : 0,
        ]);
    }

    public static function delete(int $id, array $user): void
    {
        if ($user['role'] === 'admin') {
            Database::connection()->prepare('DELETE FROM course_materials WHERE id = ?')->execute([$id]);
            return;
        }

        Database::connection()->prepare(
            'DELETE course_materials FROM course_materials
             JOIN courses ON courses.id = course_materials.course_id
             WHERE course_materials.id = ? AND courses.lecturer_id = ?'
        )->execute([$id, $user['id']]);
    }

    public static function toggle(int $id, array $user): void
    {
        if ($user['role'] === 'admin') {
            Database::connection()->prepare(
                'UPDATE course_materials SET is_active = 1 - is_active WHERE id = ?'
            )->execute([$id]);
            return;
        }

        Database::connection()->prepare(
            'UPDATE course_materials
             JOIN courses ON courses.id = course_materials.course_id
             SET course_materials.is_active = 1 - course_materials.is_active
             WHERE course_materials.id = ? AND courses.lecturer_id = ?'
        )->execute([$id, $user['id']]);
    }

    public static function searchForStudent(string $question, int $studentId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT course_materials.*, courses.code AS course_code, courses.title AS course_title
             FROM course_materials
             JOIN courses ON courses.id = course_materials.course_id
             JOIN course_enrollments ON course_enrollments.course_id = courses.id
             WHERE course_enrollments.student_id = ?
               AND course_materials.is_active = 1'
        );
        $stmt->execute([$studentId]);
        $materials = $stmt->fetchAll();

        $best = null;
        $bestScore = 0;
        $normalized = strtolower(trim($question));
        $words = preg_split('/[\s,.;:!?()\-]+/', $normalized);

        foreach ($materials as $material) {
            $haystack = strtolower(
                $material['title'] . ' ' .
                $material['description'] . ' ' .
                $material['searchable_text'] . ' ' .
                $material['course_code'] . ' ' .
                $material['course_title']
            );
            $score = 0;

            foreach ($words as $word) {
                if (strlen($word) >= 3 && strpos($haystack, $word) !== false) {
                    $score += 2;
                }
            }

            similar_text($normalized, strtolower($material['title']), $percent);
            $score += (int) floor($percent / 20);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $material;
            }
        }

        if (!$best || $bestScore < 3) {
            return null;
        }

        return [
            'material' => $best,
            'score' => $bestScore,
            'answer' => self::buildAnswer($question, $best),
        ];
    }

    private static function buildAnswer(string $question, array $material): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $material['searchable_text']));
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        $questionWords = array_filter(preg_split('/[\s,.;:!?()\-]+/', strtolower($question)), fn ($word) => strlen($word) >= 3);
        $bestSentence = $sentences[0] ?? $text;
        $bestScore = 0;

        foreach ($sentences as $sentence) {
            $score = 0;
            $lower = strtolower($sentence);
            foreach ($questionWords as $word) {
                if (strpos($lower, $word) !== false) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestSentence = $sentence;
            }
        }

        $snippet = trim($bestSentence);
        if (strlen($snippet) > 360) {
            $snippet = substr($snippet, 0, 357) . '...';
        }

        return $snippet . ' Source: ' . $material['course_code'] . ' - ' . $material['title'] . '.';
    }
}
