<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
	// Configure session using database
        $config = $e->getApplication()->getServiceManager()->get('config');
        $dbAdapter = new \Zend\Db\Adapter\Adapter($config['session_db']);
        $sessionOptions = new \Zend\Session\SaveHandler\DbTableGatewayOptions();
        $sessionTableGateway = new \Zend\Db\TableGateway\TableGateway('session', $dbAdapter);
        $saveHandler = new \Zend\Session\SaveHandler\DbTableGateway($sessionTableGateway, $sessionOptions);
        $sessionManager = new \Zend\Session\SessionManager(NULL, NULL, $saveHandler);
        $sessionManager->start();
	// Configure log exceptions to syslog
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager->getSharedManager()->attach('Zend\Mvc\Application', array(MvcEvent::EVENT_DISPATCH, MvcEvent::EVENT_DISPATCH_ERROR),
            function($e) use ($serviceManager) {
                if ($e->getParam('exception')){
                    $serviceManager->get('Zend\Log\Logger')->err($e->getParam('exception'));
                }
            }
        );
        register_shutdown_function(function () use ($serviceManager) {
            if ($e = error_get_last()) {
	        $logger = $serviceManager->get('Zend\Log\Logger');
	        $logger->err($e['message'] . " in " . $e['file'] . ' line ' . $e['line']);
                $logger->__destruct();
            }
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

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
