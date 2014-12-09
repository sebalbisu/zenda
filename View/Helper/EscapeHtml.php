<?php

class Zend_View_Helper_EscapeHtml
    extends \Zend_View_Helper_Abstract 
{
    
    public function escapeHtml(
        $string,
        $quotes = ENT_QUOTES, 
        $encoding = 'UTF-8',
        $doubleEncode = true
        )
    {
        return htmlentities(
            $string, $quotes, $encoding, $doubleEncode);
    }
}
