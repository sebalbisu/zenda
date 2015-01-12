<?php
namespace ZendA\Application\Resource;

class Logs
    extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var array
     */
    protected $_logs;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return array
     */
    public function init()
    {
        $options = $this->getOptions();
        foreach($options as $name => $config){
            if(isset($config['truncateAtStart'])) {
                if($config['truncateAtStart']){
                    $f = fopen($config['stream']
                        ['writerParams']['stream'], 'w+');
                    ftruncate($f, 0);
                    rewind($f);
                    fclose($f);
                }
                unset($config['truncateAtStart']);
            }
            $log = \Zend_Log::factory($config);
            $this->addLog($name, $log);
            \Zend_Registry::set('log-' . $name, $log);
            
            //configure depth for firebug
            if($config['stream']['writerName'] = "Firebug"){
                $this->_configureFirePHPDepth($config);
            }
        }
        return $this->_logs;
    }

    /**
     * Attach logger
     *
     * @param  string   $name
     * @param  Zend_Log $log
     * @return Zend_Application_Resource_Log
     */
    public function addLog($name, \Zend_Log $log)
    {
        $this->_logs['log-' . $name] = $log;
        return $this;
    }

    public function getLogs()
    {
        return $this->_logs;
    }
    
    protected function _configureFirePHPDepth($config)
    {
        $firephp = \Zend_Wildfire_Plugin_FirePhp::getInstance();
        if(isset($config['stream']['writerParams'])){
            $options = $config['stream']['writerParams'];
            if(isset($options['maxArrayDepth'])){
                $firephp->setOption("maxArrayDepth", 
                    $options['maxArrayDepth']);
            }
            if(isset($options['maxObjectDepth'])){
                $firephp->setOption("maxObjectDepth", 
                    $options['maxObjectDepth']);
            }                    
        }    
    }
}
