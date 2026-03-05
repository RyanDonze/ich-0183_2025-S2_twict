<?php

declare(strict_types=1);

namespace App\Controllers;

class HomeController extends \App\Controllers\AppController
{
    public function index(): void
    {
        $this->view['message.hello'] = 'Bienvenue sur la page d\'accueil';
    }
}
