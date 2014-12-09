<?php 

class Zend_View_Helper_Tidy 
    extends Zend_View_Helper_Abstract
{
    protected static $_config = 
    array(
        "show-body-only" => true,
        "wrap" => 150,
        "wrap-attributes" => 0,
        "preserve-entities" => 1,
        "output-xhtml" => 1,
        'fix-bad-comments' => 0,
        'hide-comments' => 1,
        'drop-empty-paras' => 1,
        'merge-divs' => 0,
        'merge-spans' => 0,
        'indent' => 1,
        'indent-spaces' => 4,
        'tab-size' => 4,
        'char-encoding' => 'utf8',
    );
    
    public function tidy($html, array $options = null)
    {
        $config = $options != null ?
                     array_merge(self::$_config, $options) :
                     self::$_config;
                     
        return tidy_repair_string($html, $config);
    }
    
    public static function setConfig(array $options)
    {
        self::$_config = array_merge(self::$_config, $options);
    } 
}
