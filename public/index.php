<?php

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\LecturerController;
use App\Controllers\MaterialController;
use App\Controllers\StudentController;

$route = $_GET['route'] ?? 'home';

$routes = [
    'home' => [HomeController::class, 'index'],
    'login' => [AuthController::class, 'login'],
    'login.attempt' => [AuthController::class, 'attempt'],
    'logout' => [AuthController::class, 'logout'],
    'dashboard' => [DashboardController::class, 'index'],
    'admin/users' => [AdminController::class, 'users'],
    'admin/courses' => [AdminController::class, 'courses'],
    'admin/faqs' => [AdminController::class, 'faqs'],
    'materials' => [MaterialController::class, 'index'],
    'lecturer/announcements' => [LecturerController::class, 'announcements'],
    'lecturer/messages' => [LecturerController::class, 'messages'],
    'student/messages' => [StudentController::class, 'messages'],
    'student/chatbot' => [StudentController::class, 'chatbot'],
];

if (!isset($routes[$route])) {
    http_response_code(404);
    exit('Page not found.');
}

[$controllerClass, $method] = $routes[$route];
(new $controllerClass())->$method();
