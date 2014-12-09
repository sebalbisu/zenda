<?php
namespace ZendA\Auth\Adapter;

class Doctrine implements \Zend_Auth_Adapter_Interface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    protected $entityName;
    
    protected $identityColumn;
    protected $credentialColumn;

    protected $identity;
    protected $credential;
    
    public function __construct()
    {
        $this->em = \Zend_Registry::get('doctrine')
                        ->getEntityManager();
    }
    
    public function setEntityName($name)
    {
        if(!$this->_isVariableName($name)){
            throw new \Exception('unsafe entity name');}
        $this->entityName = ucfirst($name);
        return $this;
    }
    
    public function setIdentityColumn($name)
    {
        if(!$this->_isVariableName($name)){
            throw new \Exception('unsafe identity column name');}
        $this->identityColumn = $name;
        return $this;
    }
    
    public function setCredentialColumn($name)
    {
        if(!$this->_isVariableName($name)){
            throw new \Exception('unsafe credential column name');}
        $this->credentialColumn = $name;
        return $this;
    }

    public function setIdentity($value)
    {
        $this->identity = $value;
        return $this;
    }    
        
    public function setCredential($credential)
    {
        $this->credential = self::encript($credential);
        return $this;
    }    
    
    public static function encript($credential)
    {
        return md5($credential);
    }        
    
    public function authenticate()
    {
        try {
            $result = $this->_queryForIdentityCredential();
        } catch(\Exception $e){
            //unknown error
            return new \Zend_Auth_Result(
                \Zend_Auth_Result::FAILURE,
                null,
                array('unknown error'));
        }
        
        //success 
        if($result !== null){
            //if storage is Zend_Session then regenerateId
            //when there are changes of perms, ex: login success
            if(\Zend_Auth::getInstance()->getStorage() 
                instanceof \Zend_Auth_Storage_Session){
                \Zend_Session::regenerateId();
            }
            return new \Zend_Auth_Result(
                \Zend_Auth_Result::SUCCESS,
                array('id'         => $result['id'],
                      'entityName' => $this->entityName),
                array('success'));
        }
        
        //failure
        if($this->_existIdentity()){
            return new \Zend_Auth_Result(
                \Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, 
                null,
                array('Bad Credential'));
        } else {
            return new \Zend_Auth_Result(
                \Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, 
                null,
                array('Identity not found'));
        }
    }
    
    /**
     * @return array|null
     */
    protected function _queryForIdentityCredential()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u.id');
        $qb->from('\Model\Entity\\' . $this->entityName . 'Auth', 'i');
        $qb->join('i.' . lcfirst($this->entityName), 'u');
        $qb->where('i.' . $this->identityColumn . ' = :identity');
        $qb->setParameter('identity', $this->identity);
        $qb->andwhere('i.' . $this->credentialColumn . ' = :credential');
        $qb->setParameter('credential', $this->credential);

        return $qb->getQuery()->getOneOrNullResult(
                        \Doctrine\ORM\Query::HYDRATE_SCALAR);        
        
    }
    
    /**
     * @return bool
     */
    protected function _existIdentity()
    {
        $dql = 'SELECT count(u.id) 
        FROM \Model\Entity\\' . $this->entityName . ' u
        WHERE u.' . $this->identityColumn . ' = :value';
        $query = $this->em->createQuery($dql);
        $query->setParameter('value', $this->identity);
        $result = $query->getSingleScalarResult();
        return (bool)$result;
    }
    
    /**
     * @param string $name
     * @return bool
     */
    protected function _isVariableName($name)
    {
        preg_match('/^[a-zA-Z0-9_]+$/', $name, $result);
        return !empty($result) ? true : false;
    } 
}