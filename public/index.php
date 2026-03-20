<?php
/**
 * Punto de entrada principal (Front Controller)
 *
 * Este archivo arranca la aplicacion usando el flujo oficial
 * de Laminas MVC para que las respuestas ViewModel, redirects
 * y errores se procesen correctamente.
 */

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

chdir(dirname(__DIR__));

// Carga de autoload generado por Composer.
$autoload = 'vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    echo 'Error: no se encontro vendor/autoload.php. Ejecuta composer install.';
    exit(1);
}
require $autoload;

$appConfig = require 'config/application.config.php';

Laminas\Mvc\Application::init($appConfig)->run();
