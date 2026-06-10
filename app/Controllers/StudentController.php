<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Chatbot;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Message;
use App\Models\User;

class StudentController extends Controller
{
    public function messages(): void
    {
        $user = Auth::requireRole(['student']);

        if (\is_post()) {
            \verify_csrf();
            Message::create([
                'student_id' => $user['id'],
                'lecturer_id' => (int) $_POST['lecturer_id'],
                'course_id' => $_POST['course_id'] ?? null,
                'subject' => trim($_POST['subject'] ?? ''),
                'body' => trim($_POST['body'] ?? ''),
            ]);
            \flash('success', 'Message sent to lecturer.');
            \redirect('student/messages');
        }

        $this->render('dashboard/student_messages', [
            'title' => 'Message lecturer',
            'courses' => Course::forStudent((int) $user['id']),
            'lecturers' => User::all('lecturer'),
            'messages' => Message::forStudent((int) $user['id']),
        ]);
    }

    public function chatbot(): void
    {
        $user = Auth::requireRole(['student']);

        if (\is_post()) {
            \verify_csrf();
            $question = trim($_POST['question'] ?? '');
            $response = $question ? Chatbot::answer($question, (int) $user['id']) : [
                'answer' => 'Please type a question first.',
                'source' => 'validation',
            ];

            if (($_POST['ajax'] ?? '') === '1') {
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }

            \flash('chat_answer', $response['answer']);
            \redirect('student/chatbot');
        }

        $this->render('dashboard/student_chatbot', [
            'title' => 'Academic chatbot',
            'faqs' => Faq::active(),
        ]);
    }
}
