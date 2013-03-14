<?php

namespace Application\Form;

use Zend\Form\Form;

class LogentryForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('logentry');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'logentry',
            'attributes' => array('type' => 'text'),
            'options' => array('label' => 'Logentry')
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
