<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Log\Logger' => function(){
                $logger = new Zend\Log\Logger;
                $writer = new Zend\Log\Writer\Syslog();
		$deployment = explode('/', $_ENV['DEP_NAME'])[1];
		if($deployment === 'default') {
		    $writer->addFilter(Zend\Log\Logger::ERR);
		}
                $logger->addWriter($writer);
                return $logger;
            },
        ),
    ),
);