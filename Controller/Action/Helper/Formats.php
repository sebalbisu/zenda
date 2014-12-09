<?php
namespace ZendA\Controller\Action;

class Helper_Formats
    extends \Zend_Controller_Action_Helper_ContextSwitch
{
    /**
     * Controller property to utilize for context switching
     * @var string
     */
    protected $_contextKey = 'formats';

    public function __construct()
    {
        parent::__construct();
        $this->addContexts(array(
            'ajax_json' => array(
                'suffix'    => 'ajax.json',
                'headers'   => array('Content-Type' => 'application/json'),
                'callbacks' => array(
                    'init' => 'initJsonContext',
                    'post' => 'postJsonContext'
                )
            ),
            'ajax_html'  => array(
                'suffix'    => 'ajax.html',
                'headers'   => array('Content-Type' => 'text/html'),
            ),
            'ajax_xml'  => array(
                'suffix'    => 'ajax.xml',
                'headers'   => array('Content-Type' => 'application/xml'),
            ),
        ));
    }

    public function direct($formats = null)
    {
        if(is_null($formats)){
            return $this;
        }
        $action = $this->getRequest()->getActionName();
        $this->addActionContext($action, $formats);
        $this->initContext();
        return $this;
    }
    
    /**
     * Initialize AJAX context switching
     *
     * Checks for XHR requests; if detected, attempts to perform context switch.
     *
     * @param  string $format
     * @return void
     */
    public function initContext($format = null)
    {
        $action = $this->getRequest()->getActionName();
        $context = $this->getRequest()
                        ->getParam($this->getContextParam());
        if($context === null){
            if ($this->getRequest()->isXmlHttpRequest()){
                throw new \Zend_Controller_Action_Exception('', 404);
            }
            return parent::initContext($format);
        }
        if (!$this->hasActionContext($action, $context)) {
            throw new \Zend_Controller_Action_Exception('', 404);
        }

        if ($this->getRequest()->isXmlHttpRequest()){
//            if(strlen($context) < 5 
//            || 'ajax_' != substr($context, 0, 5)){
//                throw new \Zend_Controller_Action_Exception('', 404);    
//            }        
        } else {
            if(strlen($context) > 5 
            && 'ajax_' == substr($context, 0, 5)){
                throw new \Zend_Controller_Action_Exception('', 404);    
            }
        }
        
        return parent::initContext($format);
    }
}