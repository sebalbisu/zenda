<?php
namespace ZendA\Paginator\Adapter;

class Doctrine implements \Zend_Paginator_Adapter_Interface
{
    protected $_entity;
    
    protected $_collectionGetMethod;

    protected $_count;
    
    protected $_collectionRanges = array();

    public function __construct($entity, $collectionGetMethod)
    {
        $this->_entity = $entity;
        $this->_collectionGetMethod = $collectionGetMethod;
    }

    public function getItems($offset, $itemCountPerPage = null)
    {
        $key = "r_{$offset}_{$itemCountPerPage}"; 
        if(array_key_exists($key, $this->_collectionRanges)){
            return $this->_collectionRanges[$key];
        }
        
        $this->_collectionRanges[$key] = 
            $this->_entity
                ->{$this->_collectionGetMethod}()
                ->slice($offset, $itemCountPerPage);
            
        return $this->_collectionRanges[$key];
    }

    public function count()
    {
        if(!isset($this->_count)){
            $this->_count = 
                $this->_entity
                     ->{$this->_collectionGetMethod}()
                     ->count();
        }
        return $this->_count;
    }
}