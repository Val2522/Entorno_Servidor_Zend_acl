<?php
/**
 * Configuración del Módulo Application
 * 
 * Define las rutas, servicios, controladores, vistas y otros
 * componentes del módulo de la aplicación de videojuegos retro
 * 
 * @phpstan-ignore-next-line
 */

namespace Application;

use Laminas\ServiceManager\Factory\InvokableFactory;

// Usamos nombres de tipo en texto para evitar falsos positivos del analizador.
return array(
    
    // Definición de rutas de la aplicación
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // Rutas para categorías
            'categoria' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/categoria/:id',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'categoria',
                    ),
                ),
            ),
            
            // Rutas para gestión de categorías (admin)
            'categorias' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/categorias',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Categorias',
                        'action'     => 'index',
                    ),
                ),
            ),
            'categoria-new' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/categorias/new',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Categorias',
                        'action'     => 'new',
                    ),
                ),
            ),
            'categoria-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/categorias/:id/edit',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Categorias',
                        'action'     => 'edit',
                    ),
                ),
            ),
            'categoria-delete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/categorias/:id/delete',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Categorias',
                        'action'     => 'delete',
                    ),
                ),
            ),
            
            // Rutas para gestión de artículos (admin)
            'articulo-new' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/articulos/new',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Articulos',
                        'action'     => 'new',
                    ),
                ),
            ),
            'articulo-pedidos' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/articulos/pedidos',
                    'defaults' => array(
                        'controller' => 'Application\\Controller\\Articulos',
                        'action'     => 'pedidos',
                    ),
                ),
            ),
            'articulo-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/articulos/:id/edit',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Articulos',
                        'action'     => 'edit',
                    ),
                ),
            ),
            'articulo-delete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/articulos/:id/delete',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Articulos',
                        'action'     => 'delete',
                    ),
                ),
            ),
            
            // Rutas para carrito
            'carrito-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/carrito/add/:id',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrito',
                        'action'     => 'add',
                    ),
                ),
            ),
            'carrito' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/carrito',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrito',
                        'action'     => 'index',
                    ),
                ),
            ),
            'carrito-delete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/carrito/delete/:id',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrito',
                        'action'     => 'delete',
                    ),
                ),
            ),
            'pedido' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/pedido',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrito',
                        'action'     => 'pedido',
                    ),
                ),
            ),
            'pedido-fin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/fin_pedido',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Carrito',
                        'action'     => 'finPedido',
                    ),
                ),
            ),
            
            // Rutas de autenticación
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'registro' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/registro',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'registro',
                    ),
                ),
            ),
            'perfil' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/perfil/:username',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'perfil',
                    ),
                ),
            ),
            'changepassword' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/changepassword/:username',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'changepassword',
                    ),
                ),
            ),
        ),
    ),
    
    // Configuración de controladores
    'controllers' => array(
        'aliases' => array(
            'Application\\Controller\\Index' => \Application\Controller\IndexController::class,
            'Application\\Controller\\Auth' => \Application\Controller\AuthController::class,
            'Application\\Controller\\Carrito' => \Application\Controller\CarritoController::class,
            'Application\\Controller\\Categorias' => \Application\Controller\CategoriasController::class,
            'Application\\Controller\\Articulos' => \Application\Controller\ArticulosController::class,
        ),
        'factories' => array(
            \Application\Controller\IndexController::class => InvokableFactory::class,
            \Application\Controller\AuthController::class => InvokableFactory::class,
            \Application\Controller\CarritoController::class => InvokableFactory::class,
            \Application\Controller\CategoriasController::class => InvokableFactory::class,
            \Application\Controller\ArticulosController::class => InvokableFactory::class,
        ),
    ),
    
    // Configuración de vistas
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'application/error/404',
        'exception_template'       => 'application/error/index',
        'template_path_stack' => array(
            'application' => __DIR__ . '/../view',
        ),
    ),
    
    // Configuración de servicios disponibles
    'service_manager' => array(
        'factories' => array(
            'Doctrine\ORM\EntityManager' => 'Doctrine\ORM\EntityManagerFactory',
        ),
    ),
);
