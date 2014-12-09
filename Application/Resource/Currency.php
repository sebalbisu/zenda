<?php

namespace ZendA\Application\Resource;

require_once 'Zend/Application/Resource/ResourceAbstract.php';

class Currency
extends \Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        $options = $this->getOptions();
        $currency = new \Zend_Currency($options['default']);
        \Zend_Registry::set('Zend_Currency', $currency);
        return $currency;
    }
}
