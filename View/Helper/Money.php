<?php

require_once 'Zend/View/Helper/Abstract.php';

/**
 * Similiar to currency but support multi currencies objects 
 * and simpler usage
 * 
 * $this->money(7)
 * $this->money(7, 'es_AR')
 * $this->money('es_AR')  
 * 
 */
class Zend_View_Helper_Money extends \Zend_View_Helper_Abstract
{
    /**
     * Currency objects
     *
     * @var array of Zend_Currency objects 
     */
    protected $_currencies = array();

    protected $_default;
    
    public function __construct()
    {
        if (\Zend_Registry::isRegistered('Zend_Currency')) {
            $currency = \Zend_Registry::get('Zend_Currency');
            $locale = $currency->getLocale();
            $this->_currencies[$locale] = $currency;
            $this->_default = $locale; 
        }
    }

    /**
     * Return a currency object
     * 
     * @param $valueOrLocale int|string
     * @param $locale        string
     */
    public function money($valueOrLocale = null, $locale = null)
    {
        //get only the currency by locale
        if(is_string($valueOrLocale) 
        || is_null($valueOrLocale)){
            return $this->getCurrency($valueOrLocale);
        }
        
        //using the value
        $currency = $this->getCurrency($locale);
        $currency->setValue($valueOrLocale);
        return $currency;
    }
    
    public function getCurrency($locale = null)
    {
        if(is_null($locale)){
            $locale = $this->_default;
        }
        if(!isset($this->_currencies[$locale])){
            $this->_currencies[$locale] = 
                new \Zend_Currency($locale);
        }
        return $this->_currencies[$locale];    
    }
}
