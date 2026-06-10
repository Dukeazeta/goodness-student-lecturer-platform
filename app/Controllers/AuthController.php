<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            \redirect('dashboard');
        }

        $this->render('auth/login', ['title' => 'Sign in'], 'public_layout');
    }

    public function attempt(): void
    {
        \verify_csrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            \flash('success', 'Welcome back.');
            \redirect('dashboard');
        }

        \flash('error', 'Invalid email or password.');
        \redirect('login');
    }

    public function logout(): void
    {
        Auth::logout();
        \flash('success', 'You have been signed out.');
        \redirect('login');
    }
}
