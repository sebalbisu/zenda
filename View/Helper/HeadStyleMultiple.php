<?php

require_once 'Zend/View/Helper/HeadStyle.php';

/**
 * Fix, to allow create multiple headStyle helpers
 */
class Zend_View_Helper_HeadStyleMultiple extends \Zend_View_Helper_HeadStyle
{
    public function __construct($regKey = null)
    {
        if(isset($regKey)){
            $this->_regKey = $regKey;
        }
        parent::__construct();
    }
}
