<?php

namespace ZendA\Application\Resource;

require_once(__DIR__ . '/../../Loader/ClassNsLoader.php');

/**
 * Resource Plugin to autoload classes with namespace
 *
 * ---------------------------
 * To use in application.ini:
 *
 * resources.autoload.Test.path
 *        = APPLICATION_PATH "/abc"
 * resources.autoload.Test.folder2LowerCase = 0
 *
 * ; various paths for the same namespace (FIFO order)
 *
 * resources.autoload.Lib.1.path
 *     = APPLICATION_PATH "/abc"
 * resources.autoload.Lib.1.folder2LowerCase = 1
 * resources.autoload.Lib.0.path
 *     = APPLICATION_PATH "/../library/Lib"
 * resources.autoload.Lib.0.folder2LowerCase = 0
 * ---------------------------
 *
 */
class Autoload
extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * @return void
     */
    public function init()
    {
        foreach($this->getOptions() as $namespace => $data){
            //only one path per this namespace
            if(isset($data['path'])){
                $folder2LowerCase = false;
                if(isset($data['folder2LowerCase'])){
                    $folder2LowerCase =
                        (bool) $data['folder2LowerCase'];
                }
                $this->addAutoloader($namespace,
                                     $data['path'],
                                     $folder2LowerCase);
            //various paths for this namespace
            } else {
                for($i=0; $i < count($data); $i++){
                    $folder2LowerCase = false;
                    if(isset($data[$i]['folder2LowerCase'])){
                        $folder2LowerCase =
                            (bool) $data[$i]['folder2LowerCase'];
                    }
                    $this->addAutoloader($namespace,
                                         $data[$i]['path'],
                                          $folder2LowerCase);
                }
            }
        }
        return;
    }

    /**
     * Register a namespace with a path so can autoload
     * classes with namespace
     *
     * @param     string $ns
     * @param     string $path
     * @return    void
     */
    protected function addAutoloader(
            $ns, $path, $folder2LowerCase = false)
    {
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $loader = new \ZendA\Loader\ClassNsLoader(
                            $ns, $path, $folder2LowerCase);
        $autoloader->pushAutoloader(array($loader, 'loadClass'), $ns);
    }
}
