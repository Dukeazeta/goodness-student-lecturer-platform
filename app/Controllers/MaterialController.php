<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Course;
use App\Models\Material;
use Throwable;

class MaterialController extends Controller
{
    public function index(): void
    {
        $user = Auth::requireRole(['admin', 'lecturer']);

        if (\is_post()) {
            \verify_csrf();
            $action = $_POST['action'] ?? 'create';

            try {
                if ($action === 'delete') {
                    Material::delete((int) $_POST['id'], $user);
                    \flash('success', 'Material deleted.');
                } elseif ($action === 'toggle') {
                    Material::toggle((int) $_POST['id'], $user);
                    \flash('success', 'Material status updated.');
                } else {
                    $courseId = (int) ($_POST['course_id'] ?? 0);
                    if (!$this->canUseCourse($user, $courseId)) {
                        \flash('error', 'You cannot add material to that course.');
                        \redirect('materials');
                    }

                    $file = $this->storeUpload();
                    Material::create([
                        'course_id' => $courseId,
                        'uploader_id' => $user['id'],
                        'title' => trim($_POST['title'] ?? ''),
                        'description' => trim($_POST['description'] ?? ''),
                        'searchable_text' => trim($_POST['searchable_text'] ?? ''),
                        'file_path' => $file['path'] ?? null,
                        'file_name' => $file['name'] ?? null,
                        'file_type' => $file['type'] ?? null,
                        'is_active' => $_POST['is_active'] ?? null,
                    ]);
                    \flash('success', 'Material added.');
                }
            } catch (Throwable $exception) {
                \flash('error', 'Material action failed. Check the course, file type, and required text.');
            }

            \redirect('materials');
        }

        $courses = $user['role'] === 'admin' ? Course::all() : Course::forLecturer((int) $user['id']);
        $materials = $user['role'] === 'admin' ? Material::all() : Material::forLecturer((int) $user['id']);

        $this->render('dashboard/materials', [
            'title' => 'Course materials',
            'user' => $user,
            'courses' => $courses,
            'materials' => $materials,
        ]);
    }

    private function canUseCourse(array $user, int $courseId): bool
    {
        if ($user['role'] === 'admin') {
            return $courseId > 0;
        }

        foreach (Course::forLecturer((int) $user['id']) as $course) {
            if ((int) $course['id'] === $courseId) {
                return true;
            }
        }

        return false;
    }

    private function storeUpload(): array
    {
        if (empty($_FILES['material_file']['name'])) {
            return [];
        }

        if ($_FILES['material_file']['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed.');
        }

        $original = basename($_FILES['material_file']['name']);
        $extension = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'txt'];

        if (!in_array($extension, $allowed, true)) {
            throw new \RuntimeException('Unsupported material file type.');
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/materials';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $storedName = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $target = $uploadDir . '/' . $storedName;

        if (!move_uploaded_file($_FILES['material_file']['tmp_name'], $target)) {
            throw new \RuntimeException('Could not store uploaded material.');
        }

        return [
            'path' => 'uploads/materials/' . $storedName,
            'name' => $original,
            'type' => $extension,
        ];
    }
}
