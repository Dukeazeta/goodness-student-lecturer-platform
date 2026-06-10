<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Message;

class LecturerController extends Controller
{
    public function announcements(): void
    {
        $user = Auth::requireRole(['lecturer', 'admin']);

        if (\is_post()) {
            \verify_csrf();
            $action = $_POST['action'] ?? 'create';
            if ($action === 'delete') {
                Announcement::delete((int) $_POST['id'], (int) $user['id'], $user['role'] === 'admin');
                \flash('success', 'Announcement deleted.');
            } else {
                Announcement::create([
                    'author_id' => $user['id'],
                    'course_id' => $_POST['course_id'] ?? null,
                    'title' => trim($_POST['title'] ?? ''),
                    'body' => trim($_POST['body'] ?? ''),
                ]);
                \flash('success', 'Announcement posted.');
            }
            \redirect('lecturer/announcements');
        }

        $courses = $user['role'] === 'admin' ? Course::all() : Course::forLecturer((int) $user['id']);
        $this->render('dashboard/lecturer_announcements', [
            'title' => 'Announcements',
            'user' => $user,
            'courses' => $courses,
            'announcements' => Announcement::allForUser($user),
        ]);
    }

    public function messages(): void
    {
        $user = Auth::requireRole(['lecturer']);

        if (\is_post()) {
            \verify_csrf();
            Message::reply((int) $_POST['id'], (int) $user['id'], trim($_POST['reply'] ?? ''));
            \flash('success', 'Reply sent.');
            \redirect('lecturer/messages');
        }

        $this->render('dashboard/lecturer_messages', [
            'title' => 'Student messages',
            'messages' => Message::forLecturer((int) $user['id']),
        ]);
    }
}
