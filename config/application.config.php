<?php
/**
 * Configuración principal de la aplicación Zend Framework
 * 
 * Este archivo carga los módulos y la configuración global
 * del framework para la tienda de videojuegos retro
 */

return array(
    'modules' => array(
        'Laminas\\Router',
        'Laminas\\Validator',
        'Application',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);
