<?php
/**
 * DefiniciÃ³n del MÃ³dulo Application
 * 
 * Este archivo configura el mÃ³dulo y gestiona su ciclo de vida
 * en la aplicaciÃ³n Zend Framework
 */

namespace Application;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    /**
     * Retorna la configuraciÃ³n del mÃ³dulo
     * 
     * @return array ConfiguraciÃ³n del mÃ³dulo
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * Retorna la configuraciÃ³n de autoload
     * 
     * @return array ConfiguraciÃ³n de autoload
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}


