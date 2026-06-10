<?php

namespace App\Models;

use App\Core\Database;

class Chatbot
{
    public static function answer(string $question, int $studentId): array
    {
        $smallTalk = self::smallTalk($question);
        if ($smallTalk) {
            self::log($studentId, $question, $smallTalk, 'smalltalk');
            return [
                'answer' => $smallTalk,
                'source' => 'smalltalk',
            ];
        }

        $faq = self::searchFaq($question);
        $material = Material::searchForStudent($question, $studentId);

        if ($faq && (!$material || $faq['score'] >= $material['score'])) {
            self::log($studentId, $question, $faq['answer'], 'faq');
            return [
                'answer' => $faq['answer'],
                'source' => 'faq',
            ];
        }

        if ($material) {
            self::log($studentId, $question, $material['answer'], 'material');
            return [
                'answer' => $material['answer'],
                'source' => 'material',
            ];
        }

        $answer = 'I could not find a confident answer in the general FAQ or your enrolled course materials. Your question has been saved for review.';
        self::log($studentId, $question, $answer, 'fallback');
        return [
            'answer' => $answer,
            'source' => 'fallback',
        ];
    }

    private static function smallTalk(string $question): ?string
    {
        $normalized = strtolower(trim($question));
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized ?? '');

        if ($normalized === '') {
            return null;
        }

        if (preg_match('/\b(thank you|thanks|thank|appreciate it)\b/', $normalized)) {
            return 'You are welcome. Ask me any question from your course materials or general platform information.';
        }

        if (preg_match('/\b(bye|goodbye|see you|later)\b/', $normalized)) {
            return 'Goodbye. Come back whenever you need help with your coursework.';
        }

        if (preg_match('/\b(how are you|how far|how you dey|are you okay)\b/', $normalized)) {
            return 'I am ready to help with course materials, announcements, messages, and general student support questions.';
        }

        if (preg_match('/\b(who are you|what are you|your name)\b/', $normalized)) {
            return 'I am the ClassBridge academic chatbot. I help students find answers from general FAQs and enrolled course materials.';
        }

        if (preg_match('/\b(help|what can you do|how can you help|what do you do)\b/', $normalized)) {
            return 'I can answer general platform questions and questions from materials uploaded for your enrolled courses.';
        }

        if (preg_match('/\b(hi|hello|hey|good morning|good afternoon|good evening|greetings)\b/', $normalized)) {
            return 'Hello. You can ask me about general school questions or materials from your enrolled courses.';
        }

        return null;
    }

    private static function searchFaq(string $question): ?array
    {
        $entries = Faq::active();
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

        if (!$best || $bestScore < 3) {
            return null;
        }

        return [
            'answer' => $best['answer'],
            'score' => $bestScore,
        ];
    }

    private static function log(int $userId, string $question, string $answer, string $source): void
    {
        Database::connection()->prepare(
            'INSERT INTO chat_logs (user_id, question, answer, source) VALUES (?, ?, ?, ?)'
        )->execute([$userId, $question, $answer, $source]);
    }
}
