<?php
namespace ZendA\Doctrine\Model\Entity;

class Base
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return \Zend_Registry::get('doctrine')
                          ->getEntityManager();
    }
    
    public function getRepository()
    {
        
    }
}