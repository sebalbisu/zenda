<?php

require_once 'Zend/View/Helper/HeadLink.php';

/**
 * Fix, to allow create multiple headLink helpers
 */
class Zend_View_Helper_HeadLinkMultiple extends \Zend_View_Helper_HeadLink 
{
    public function __construct($regKey = null)
    {
        if(isset($regKey)){
            $this->_regKey = $regKey;
        }
        parent::__construct();
    }
}
