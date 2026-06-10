<?php

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\LecturerController;
use App\Controllers\MaterialController;
use App\Controllers\StudentController;

$route = $_GET['route'] ?? route_from_request();

function route_from_request(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $scriptBase = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptBase = $scriptBase === '/' ? '' : rtrim($scriptBase, '/');

    if ($scriptBase !== '' && route_starts_with($path, $scriptBase . '/')) {
        $path = substr($path, strlen($scriptBase));
    }

    $route = trim($path, '/');
    if ($route === '' || $route === 'index.php' || $route === 'public' || $route === 'public/index.php') {
        return 'home';
    }

    if (route_starts_with($route, 'public/')) {
        $route = substr($route, 7);
    }

    return trim($route, '/') ?: 'home';
}

function route_starts_with(string $value, string $prefix): bool
{
    return substr($value, 0, strlen($prefix)) === $prefix;
}

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
