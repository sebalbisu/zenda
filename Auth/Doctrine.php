<?php
namespace ZendA\Auth;

class Doctrine extends \Zend_Auth
{
    const GUEST = 'Guest';
    
    protected static $_instance = null;    
    
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    public function getUserType()
    {
        $identity = \Zend_Auth::getInstance()->hasIdentity() ? 
            \Zend_Auth::getInstance()->getIdentity() : 
            array('entityName' => self::GUEST);
        return $identity['entityName'];  
    }
}