<?php

require_once 'Zend/View/Helper/HeadScript.php';

/**
 * Fix, to allow create multiple headScript helpers
 */
class Zend_View_Helper_HeadScriptMultiple extends \Zend_View_Helper_HeadScript
{
    public function __construct($regKey = null)
    {
        if(isset($regKey)){
            $this->_regKey = $regKey;
        }
        parent::__construct();
    }
}
