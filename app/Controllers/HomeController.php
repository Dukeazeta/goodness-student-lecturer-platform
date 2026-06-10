<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('public/home', [
            'title' => 'Welcome',
            'ctaRoute' => Auth::check() ? 'dashboard' : 'login',
        ], 'public_layout');
    }
}
