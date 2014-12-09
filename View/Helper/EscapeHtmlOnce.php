<?php

class Zend_View_Helper_EscapeHtmlOnce
    extends \Zend_View_Helper_Abstract 
{
    
    public function escapeHtmlOnce(
        $string,
        $quotes = ENT_QUOTES, 
        $encoding = 'UTF-8'
        )
    {
        return htmlentities(
            $string, $quotes, $encoding, $doubleEncode = false );
    }
}
