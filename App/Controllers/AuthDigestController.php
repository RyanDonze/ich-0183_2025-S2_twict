<?php

declare(strict_types=1);

namespace App\Controllers;

use \App\Models\User;

class AuthDigestController extends AppController
{
    private const REALM = 'oPh?\dRG>B413a;E:5';

    public function login(): void
    {
        if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
            header('WWW-Authenticate: Basic realm="' . self::REALM . '"');
            header('HTTP/1.1 401 Unauthorized', true, 401);
            return;
        }


        $user = User::findByMailAddressAndPassword($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

        if ($user == null) {
            $this->flash->danger('Le nom d\'utilisateur est invalide');
            $this->redirect('/auth');
        }


        $_SESSION['user'] = $user;
        $this->flash->success('Le processus de connexion a réussi');
        $this->redirect('/auth');
    }

    public function loginCancel(): void
    {
        $this->flash->warning('Le processus de connexion a été annulé');
        $this->redirect('/auth');
    }

    public function logout(): void
    {
        session_destroy();
        session_start();

        header('WWW-Authenticate: Basic realm="' . self::REALM . '"');
        header('HTTP/1.1 401 Unauthorized', true, 401);

        $this->flash->success('Le processus de déconnexion a réussi');
    }

    public function logoutCancel(): void
    {
        $this->flash->success('Le processus de déconnexion a réussi');
        $this->redirect('/auth');
    }

    function http_digest_parse($txt)
    {
        $needed_parts = ['nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1];
        $data = [];
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
    }
}
