<?php
/**
 * Usage:
 * 
 * $this->_helper->flashMessenger(array('error' => 'nnsnsss'));
 * $this->_helper->flashMessenger(array('error' => 'nnsnsss2'));
 * $this->_helper->flashMessenger(array('highlight' => 'nnsnsss3'));
 */
class Zend_View_Helper_FlashMessenger extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    private $_flashMessenger = null;
    
    private $_id = 'flashMessages';
    
    private $_template = '<p class="%s">%s</p>';
    
    private $_useJqueryTemplate = false;
    
    private $_jqueryTemplate = array(
        'error'     => '<div class="ui-state-error ui-corner-all"><p class="%s"><span class="ui-icon ui-icon-alert"></span><span class="flash-message">%s</span></p></div>',
        'highlight' => '<div class="ui-state-highlight ui-corner-all"><p class="%s"><span class="ui-icon ui-icon-info"></span><span class="flash-message">%s</span></p></div>',
    );
    
    private $_useJqueryAnimation = false;
    
    private $_jqueryAnimation = '<script type="text/javascript">
        $("#[[id]] > *:first-child").fadeIn([[fade]], function(){
            $(this).delay([[delay]]).fadeOut([[fade]]);
            $(this).next().fadeIn([[fade]], arguments.callee)
          });
        </script>';
    
    private $_animation = array();
    
    public function flashMessenger()
    {
        return $this;
    }
    
    public function setId($id)
    {
        $this->_id = $id;
    }

    public function setTemplate($templ)
    {
        $this->_template = $templ;
        return $this;
    }    
    
    public function useJqueryTemplate()
    {
        $this->_useJqueryTemplate = true;
        return $this;
    }
    
    public function useJqueryAnimation($delay = 4000, $fade = 1000)
    {
        $this->_useJqueryAnimation = true;
        $this->_animation['delay'] = $delay;
        $this->_animation['fade'] = $fade;
        return $this;
    }        
    
    public function __toString()
    {
        $flashMessenger = $this->_getFlashMessenger();

        //get messages from previous requests
        $messages = $flashMessenger->getMessages();

        //add any messages from this request
        if ($flashMessenger->hasCurrentMessages()) {
            $messages = array_merge(
                $messages,
                $flashMessenger->getCurrentMessages()
            );
            //we don't need to display them twice.
            $flashMessenger->clearCurrentMessages();
        }

        $htmlMsgs = array();
        
        //process messages
        foreach ($messages as $message)
        {
            if (is_array($message)) {
                list($key,$message) = each($message);
                $template = $this->_getMessageTemplate($key);
                $htmlMsgs[] = sprintf($template, $key, $message);
            }
        }
        return $this->_finishHtml($htmlMsgs);    
    }

    /**
     * Lazily fetches FlashMessenger Instance.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    protected function _getFlashMessenger()
    {
        if (null === $this->_flashMessenger) {
            $this->_flashMessenger =
                Zend_Controller_Action_HelperBroker::getStaticHelper(
                    'FlashMessenger');
        }
        return $this->_flashMessenger;
    }
    
    protected function _getMessageTemplate($type)
    {        
        if($this->_useJqueryTemplate){
            $template = $this->_jqueryTemplate[$type];
        } else {
            if(is_array($this->_template)){
                $template = $this->_template[$type];
            } else {
                $template = $this->_template;
            }
        }
        return $template;
    }
    
    protected function _finishHtml($htmlMsgs)
    {
        if(empty($htmlMsgs)){ return '';}
        
        if($this->_useJqueryAnimation){
            //set display none
            array_walk($htmlMsgs, function(&$msg){
                $msg = substr_replace($msg, ' style="display:none"', 4, 0);
            });
        }
        $htmlMsgs = implode('', $htmlMsgs);
        $output = "<div id='{$this->_id}'>$htmlMsgs</div>";

        if($this->_useJqueryAnimation){
            //add javascript
            $vars = array(
                '[[id]]'    => $this->_id,
                '[[delay]]' => $this->_animation['delay'],
                '[[fade]]'  => $this->_animation['fade'],
            );
            $output .= str_replace(array_keys($vars), 
                                  array_values($vars), 
                                  $this->_jqueryAnimation);
        }
        return $output;
    }

}