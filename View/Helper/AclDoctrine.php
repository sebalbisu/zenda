<?php

require_once 'Zend/View/Helper/Abstract.php';


class Zend_View_Helper_AclDoctrine
    extends Zend_View_Helper_Abstract 
{
    /**
     * @var \ZendA\Auth\Doctrine
     */
    protected $auth;
    
    /**
     * @var Zend_Acl
     */
    protected $acl;
    
    public function __construct()
    {
        $this->auth = \ZendA\Auth\Doctrine::getInstance();
        $this->acl = \Zend_Registry::get('acl_site');
    }
    
    public function aclDoctrine()
    {
        return $this;
    }
    
    public function isAllowed($resource = null, $perms = null)
    {
        $userType =  $this->auth->getUserType();
        return $this->acl->isAllowed($userType, $resource, $perms);
    }
}
