<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Message;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): void
    {
        $user = Auth::requireLogin();

        if ($user['role'] === 'admin') {
            $this->render('dashboard/admin', [
                'title' => 'Admin dashboard',
                'user' => $user,
                'counts' => User::counts(),
                'courses' => Course::all(),
                'announcements' => Announcement::allForUser($user),
                'faqs' => Faq::all(),
            ]);
            return;
        }

        if ($user['role'] === 'lecturer') {
            $this->render('dashboard/lecturer', [
                'title' => 'Lecturer dashboard',
                'user' => $user,
                'courses' => Course::forLecturer((int) $user['id']),
                'messages' => Message::forLecturer((int) $user['id']),
                'announcements' => Announcement::allForUser($user),
            ]);
            return;
        }

        $this->render('dashboard/student', [
            'title' => 'Student dashboard',
            'user' => $user,
            'courses' => Course::forStudent((int) $user['id']),
            'messages' => Message::forStudent((int) $user['id']),
            'announcements' => Announcement::allForUser($user),
        ]);
    }
}
