<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class AppController extends \Core\Controller
{
    protected \Core\View $view;
    protected \App\Helpers\FlashNotificationHelper $flash;

    protected function before(): bool
    {
        $this->view = new \Core\View(templatePath: dirname(__DIR__) . '/views/');
        $this->view->setFilename(strtolower($this->routeParams['controller']) . DIRECTORY_SEPARATOR . strtolower($this->routeParams['action']));

        $this->flash = new \App\Helpers\FlashNotificationHelper();

        return parent::before();
    }

    protected function after(): void
    {
        $this->view['debug.session'] = $_SESSION;

        $this->flash->flush($this->view);
        $this->view->render();

        parent::after();
    }

    protected function redirect(string $location, int $code = 302): void
    {
        header("Location: {$location}", true, $code);
        exit;
    }
}
