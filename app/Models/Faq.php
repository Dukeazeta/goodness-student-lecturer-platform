<?php

namespace App\Models;

use App\Core\Database;

class Faq
{
    public static function all(): array
    {
        return Database::connection()->query('SELECT * FROM faq_entries ORDER BY category, question')->fetchAll();
    }

    public static function active(): array
    {
        return Database::connection()->query('SELECT * FROM faq_entries WHERE is_active = 1 ORDER BY category, question')->fetchAll();
    }

    public static function create(array $data): void
    {
        Database::connection()->prepare(
            'INSERT INTO faq_entries (category, question, keywords, answer, is_active) VALUES (?, ?, ?, ?, ?)'
        )->execute([
            $data['category'],
            $data['question'],
            $data['keywords'],
            $data['answer'],
            isset($data['is_active']) ? 1 : 0,
        ]);
    }

    public static function update(int $id, array $data): void
    {
        Database::connection()->prepare(
            'UPDATE faq_entries SET category = ?, question = ?, keywords = ?, answer = ?, is_active = ? WHERE id = ?'
        )->execute([
            $data['category'],
            $data['question'],
            $data['keywords'],
            $data['answer'],
            isset($data['is_active']) ? 1 : 0,
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM faq_entries WHERE id = ?')->execute([$id]);
    }

    public static function answer(string $question, ?int $userId): array
    {
        $entries = self::active();
        $best = null;
        $bestScore = 0;
        $normalized = strtolower(trim($question));

        foreach ($entries as $entry) {
            $haystack = strtolower($entry['question'] . ' ' . $entry['keywords']);
            $score = 0;
            foreach (preg_split('/[\s,;]+/', $normalized) as $word) {
                if (strlen($word) >= 3 && strpos($haystack, $word) !== false) {
                    $score += 2;
                }
            }
            similar_text($normalized, strtolower($entry['question']), $percent);
            $score += (int) floor($percent / 20);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $entry;
            }
        }

        if ($best && $bestScore >= 3) {
            $answer = $best['answer'];
            $source = 'faq';
        } else {
            $answer = 'I could not find a confident answer yet. Your question has been saved so an administrator can add it to the FAQ list.';
            $source = 'fallback';
        }

        Database::connection()->prepare(
            'INSERT INTO chat_logs (user_id, question, answer, source) VALUES (?, ?, ?, ?)'
        )->execute([$userId, $question, $answer, $source]);

        return compact('answer', 'source');
    }
}
