<?php

declare(strict_types=1); // dire à PHP qu'il doit verifier les types

namespace App\Controllers;

use App\Models\User;

class AuthFormController extends AuthController
{
    public function login(): void {}

    public function login_post(): void
    {
        $user = User::findByMailAddressAndPassword($_POST['user']['mailAddress'], $_POST['user']['password']);
        if ($user === null) {
            $this->flash->warning('Identifiants incorrects');
            $this->redirect('./login');
        }
        $_SESSION['user'] = $user;
        $this->flash->success('Le processus de connexion a réussi');
        $this->redirect('/');
    }

    public function logout(): void
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                break;

            case 'POST':
                session_destroy();
                session_start();

                header('HTTP/1.1 401 Unauthorized', true, 401);

                $this->flash->success('Le processus de déconnexion a réussi');
                break;
        }
    }
}
