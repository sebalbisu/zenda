<?php

require_once 'Zend/View/Helper/Abstract.php';
require_once 'ZendA/View/Helper/HeadStyleMultiple.php';

class Zend_View_Helper_Styles
    extends Zend_View_Helper_Abstract 
{
    const PRE_REGKEY = __CLASS__;
    
    const SEPARATOR_REGKEY = '_';
    
    const DEFAULT_SECTION = 'default';
    
    protected $_sections = array();
    
    public function __construct()
    {
        $this->_sections[self::DEFAULT_SECTION] = 
            new Zend_View_Helper_HeadStyleMultiple(
                $this->_getRegKey(self::DEFAULT_SECTION));      
    }
    
    protected function _getRegKey($suffix)
    {
        return self::PRE_REGKEY 
             . self::SEPARATOR_REGKEY 
             . $suffix;
    }
    
    public function styles($section = self::DEFAULT_SECTION)
    {
        if(!isset($this->_sections[$section])){
            $this->_sections[$section] = 
                new Zend_View_Helper_HeadStyleMultiple(
                    $this->_getRegKey($section));
        }
        return $this->_sections[$section];  
    }
}
