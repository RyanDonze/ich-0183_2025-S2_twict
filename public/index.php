<?php

declare(strict_types=1);

/**
 * Composer
 */
require_once(dirname(__DIR__) . '/vendor/autoload.php');

/**
 * Configure PHP
 */
ini_set('xdebug.var_display_max_depth', 10);
ini_set('error_log', dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.log');

/**
 * Error and Exception handling
 */
$errorHandler = new Core\ErrorHandler();

/**
 * TLS Security
 */
header('Strict-Transport-Security: max-age= 31536000; includeSubDomains');
if (
    (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')
    && (empty($_SERVER['HTTP_X_FORWARDED_SSL']) || $_SERVER['HTTP_X_FORWARDED_SSL'] !== 'on')
    && (empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https')
) {
    header('HTTP/1.1 301 Moved Permanently');
    header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
    exit;
}


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('{controller}/{action}');
$router->add('{controller}', ['action' => 'index']);
$router->add('', ['controller' => 'Home', 'action' => 'index']);

// Dispatch request
$router->dispatch($_SERVER['REQUEST_URI']);
