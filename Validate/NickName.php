<?php

namespace ZendA\Validate;

class NickName extends \Zend_Validate_Abstract
{
    const INVALID_TYPE   = 'nickNameInvalidType';
    const INVALID        = 'nickNameInvalid';
    const STRING_EMPTY   = 'nickNameStringEmpty';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_TYPE  => "Invalid type given. String expected",
        self::INVALID       => "'%value%' contains invalid characters",
        self::STRING_EMPTY  => "'%value%' is an empty string"
    );
    
    protected $_extraChars = '';
    
    public function __construct($options = null)
    {
        if(is_null($options)){ return; }
        
        if(isset($options['extraChars'])){
            $this->setExtraChars($options['extraChars']); 
        }
    }

    /**
     * Returns the extraChars option
     *
     * @return boolean
     */
    public function getExtraChars()
    {
        return $this->_extraChars;
    }

    /**
     * Sets the extraChars option
     *
     * @param boolean $extraChars
     * @return Zend_Filter_Alpha Provides a fluent interface
     */
    public function setExtraChars($extraChars)
    {
        $this->_extraChars = preg_quote($extraChars);
        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value contains only alphabetic characters
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {

        if (!is_string($value)) {
            $this->_error(self::INVALID);
            return false;
        }

        $this->_setValue($value);

        if ('' === $value) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }
        
        $digits = '0-9';
        $letters = 'a-z';
        $dash = '_';
        $extras = $this->_extraChars;
        
        $fistWord = '[' . $letters . ']';
        $otherWords = '[' . $digits . $letters . $dash . $extras . ']';
        $pattern = '/^'. $fistWord . '{1}' . $otherWords . '*' .'$/';   
        
        if(!preg_match($pattern, $value)){
            $this->_error(self::INVALID);
            return false;
        }

        return true;
    }

}
