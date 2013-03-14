<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Log\Logger' => function(){
                $logger = new Zend\Log\Logger;
                $writer = new Zend\Log\Writer\Syslog();
                $writer->addFilter(Zend\Log\Logger::ERR);
                $logger->addWriter($writer);
                return $logger;
            },
        ),
    ),
);