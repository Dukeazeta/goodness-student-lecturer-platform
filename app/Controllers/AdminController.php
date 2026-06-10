<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;
use App\Models\Faq;
use App\Models\User;
use Throwable;

class AdminController extends Controller
{
    public function users(): void
    {
        Auth::requireRole(['admin']);

        if (\is_post()) {
            \verify_csrf();
            try {
                $action = $_POST['action'] ?? 'create';
                $id = (int) ($_POST['id'] ?? 0);
                if ($action === 'delete') {
                    User::delete($id);
                    \flash('success', 'User deleted.');
                } elseif ($action === 'update') {
                    User::update($id, $_POST);
                    \flash('success', 'User updated.');
                } else {
                    User::create($_POST);
                    \flash('success', 'User created.');
                }
            } catch (Throwable $exception) {
                \flash('error', 'User action failed. Check for duplicate emails or missing fields.');
            }
            \redirect('admin/users');
        }

        $this->render('dashboard/admin_users', [
            'title' => 'Manage users',
            'users' => User::all(),
        ]);
    }

    public function courses(): void
    {
        Auth::requireRole(['admin']);

        if (\is_post()) {
            \verify_csrf();
            try {
                $action = $_POST['action'] ?? 'create';
                $id = (int) ($_POST['id'] ?? 0);
                if ($action === 'delete') {
                    Course::delete($id);
                    \flash('success', 'Course deleted.');
                } elseif ($action === 'enroll') {
                    Course::enroll((int) $_POST['student_id'], (int) $_POST['course_id']);
                    \flash('success', 'Student assigned to course.');
                } elseif ($action === 'unenroll') {
                    Course::unenroll((int) $_POST['student_id'], (int) $_POST['course_id']);
                    \flash('success', 'Student removed from course.');
                } elseif ($action === 'update') {
                    Course::update($id, $_POST);
                    \flash('success', 'Course updated.');
                } else {
                    Course::create($_POST);
                    \flash('success', 'Course created.');
                }
            } catch (Throwable $exception) {
                \flash('error', 'Course action failed. Check course code and lecturer selection.');
            }
            \redirect('admin/courses');
        }

        $this->render('dashboard/admin_courses', [
            'title' => 'Manage courses',
            'courses' => Course::all(),
            'lecturers' => User::all('lecturer'),
            'students' => User::all('student'),
            'enrollments' => Course::enrollments(),
        ]);
    }

    public function faqs(): void
    {
        Auth::requireRole(['admin']);

        if (\is_post()) {
            \verify_csrf();
            try {
                $action = $_POST['action'] ?? 'create';
                $id = (int) ($_POST['id'] ?? 0);
                if ($action === 'delete') {
                    Faq::delete($id);
                    \flash('success', 'FAQ deleted.');
                } elseif ($action === 'update') {
                    Faq::update($id, $_POST);
                    \flash('success', 'FAQ updated.');
                } else {
                    Faq::create($_POST);
                    \flash('success', 'FAQ created.');
                }
            } catch (Throwable $exception) {
                \flash('error', 'FAQ action failed. Check all fields.');
            }
            \redirect('admin/faqs');
        }

        $this->render('dashboard/admin_faqs', [
            'title' => 'Manage chatbot FAQ',
            'faqs' => Faq::all(),
        ]);
    }
}
