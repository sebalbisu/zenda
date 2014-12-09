<?php 
namespace ZendA\Controller;

class Action extends \Zend_Controller_Action
{
    const IS_FORWARD = 'isForward';
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Zend_Acl
     */
    protected $acl;
    
    public function __construct(
        \Zend_Controller_Request_Abstract $request, 
        \Zend_Controller_Response_Abstract $response, 
        array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);

        if(\Zend_Registry::isRegistered('doctrine'))
            $this->em = \Zend_Registry::get('doctrine')
                          ->getEntityManager();
        
        if(\Zend_Registry::isRegistered('acl_site'))
            $this->acl = \Zend_Registry::get('acl_site');
    }

    
    public function setNoRender()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }
    
    /**
     * @return \Zend_Layout
     */
    public function layout()
    {
        return $this->_helper->layout();
    }
    
    /**
     * @return \Zend_Controller_Action_Helper_ViewRenderer
     */    
    public function viewRenderer()
    {
        return $this->_helper->viewRenderer();
    }
    
    /**
     * @return \Zend_Controller_Router_Rewrite
     */     
    public function getRouter()
    {
        return $this->getFrontController()->getRouter();
    }

    /**
     * @return \Zend_Controller_Request_Http
     */         
    public function getRequestHttp()
    {
        return $this->getRequest();
    }
/*
    public function forward($action, $controller = null, $module = null, array $params = null)
    {
        \Zend_Registry::set(self::IS_FORWARD, true);
        $this->_forward($action, $controller, $module, $params);
    }
*/
    /**
     * Front Pages only are accessed by a explicit route or by
     * by a forward from other action in any controller.
     */
    public function denyDefaultRouteAccess()
    {
        if(\Zend_Registry::isRegistered(self::IS_FORWARD)) 
        { return; }
        
        $routeName = $this->getFrontController()
                          ->getRouter()
                          ->getCurrentRouteName();
        if($routeName == 'default'){
            throw new \Zend_Controller_Action_Exception('Access Denied', 404);
        }
    }
    
    public function loadNavigation($path, $varName)
    {
        if (preg_match('#\.\.[\\\/]#', $path)) {
            throw new \Exception('Requested scripts may not 
            include parent directory traversal 
            ("../", "..\\" notation)');;
        }
        $nav = new \Zend_Navigation();
        $pages = include_once APPLICATION_PATH . '/navigations/'. $path;
        $nav->addPages($pages);
        $this->view->placeholder('navigation')->$varName = $nav;
        return $nav;        
    }
    
    public function onlyAjax()
    {
        if(!$this->_request->isXmlHttpRequest()){
            throw new \Zend_Controller_Action_Exception('', 404);
        }
        $this->layout()->disableLayout();
    }
    
    public function onlyMethod($method)
    {
        $valid = false;
        $reqMethod = strtoupper($this->_request->getMethod());
        if(is_array($method)){
            foreach($method as $methodName){
                $valid |= strtoupper($methodName) === $reqMethod; 
            }
        } else {
            $valid = strtoupper($method) === $reqMethod ? 
                        true : false;    
        }
        
        if(!$valid){
            throw new \Zend_Controller_Action_Exception('', 404);
        }
    }
}