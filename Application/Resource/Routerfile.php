<?php

namespace ZendA\Application\Resource;

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';


class Routerfile
extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Zend_Controller_Router_Rewrite
     */
    protected $_router;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_Controller_Router_Rewrite
     */
    public function init()
    {
        return $this->getRouter();
    }

    /**
     * Retrieve router object
     *
     * @return Zend_Controller_Router_Rewrite
     */
    public function getRouter()
    {
        if (null === $this->_router) {
            $bootstrap = $this->getBootstrap();
            $bootstrap->bootstrap('FrontController');
            $this->_router = $bootstrap->getContainer()
                                       ->frontcontroller
                                       ->getRouter();

            $options = $this->getOptions();
            if (!isset($options['path'])) {
                throw new \Exception(
                    'the Path to the file for routes is required');
            }
            $path = $options['path'];
            $type = isset($options['type']) ? 
                            ucfirst(strtolower($options['type'])) 
                            : 'Xml';
                
            if($type == 'Array'){
                $config = new Zend_Config(require $path); 
            } else {
                $class = "Zend_Config_$type";
                $config = new $class($path, APPLICATION_ENV);
            }
            $this->_router->addConfig($config);
        }
        return $this->_router;
    }
}
