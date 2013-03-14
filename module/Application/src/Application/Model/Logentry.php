<?php

namespace Application\Model;

use \DateTime;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Logentry implements InputFilterAwareInterface {
    public $logentry;
    public $date;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->logentry = (isset($data['logentry'])) ? $data['logentry'] : null;
        $this->date = new DateTime('now');
    }
    public function setInputFilter(InputFilterInterface $inputFilter) {}
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                'name'     => 'logentry',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}
