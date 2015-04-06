<?php

namespace ZendA\Application\Resource;

//require_once 'Zend/Application/Resource/ResourceAbstract';

class Html
extends \Zend_Application_Resource_ResourceAbstract
{
    protected $_view;
    /**
     * @return void
     */
    public function init()
    {
        $this->_view = $this->getBootstrap()
                            ->bootstrap('view')
                            ->view;
                            
        $options = $this->getOptions();
        if(isset($options['metas'])){             
            $this->_initMetas($options['metas']);
        }
        if(isset($options['links'])){ 
            $this->_initLinks($options['links']);
        }
        if(isset($options['scripts'])){ 
            $this->_initScripts($options['scripts']);
        }
        if(isset($options['title'])){ 
            $this->_initTitle($options['title']);
        }
        
        return;
    }
    
    protected function _initMetas($options)
    {
        //names
        if(isset($options['name'])){        
            foreach($options['name'] as $name => $content){
                $this->_view->headMeta()->appendName($name, (string) $content);
            }
        }
        //httpEquiv
        if(isset($options['httpEquiv'])){        
            foreach($options['httpEquiv'] as $name => $content){
                $this->_view->headMeta()->appendHttpEquiv($name, (string) $content);
            }
        }       
        return;    
    }
    
    protected function _initLinks($options)
    {
        foreach($options as $sectionName => $section){
            foreach($section as $link){
                $this->_view->links($sectionName)
                            ->appendStylesheet((string)$link);
            }
        }
        return;    
    }

    protected function _initScripts($options)
    {
        foreach($options as $sectionName => $section){
            //file
            if(isset($section['file'])){
                foreach($section['file'] as $path){
                    $this->_view->scripts($sectionName)
                                ->appendFile((string)$path);
                }               
            } 
            //script    
            if (isset($section['script'])) {
                 foreach($section['script'] as $script){
                    $this->_view->scripts($sectionName)
                                ->appendScript((string)$script);
                }           
            }
        }
        return;    
    }

    protected function _initTitle($options)
    {
        //text
        if(isset($options['text'])){
            $this->_view->headTitle($options['text']);
        }
        //separator
        if(isset($options['separator'])){
            $this->_view->headTitle()
                        ->getContainer()
                        ->setSeparator(
                            (string)$options['separator']);
        }       
        return;
    }           
}
