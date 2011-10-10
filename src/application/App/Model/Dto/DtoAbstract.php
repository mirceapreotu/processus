<?php
/**
 * App_Model_Dto_DtoAbstract
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dto
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Model_Dto_DtoAbstract
 *
 * @category	meetidaaa.com
 * @package		App_Model_Dto
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 *
 *
 * @property    bool $exists
 *
 */
 
abstract class App_Model_Dto_DtoAbstract
{

    /**
     * different getId() handling marker
     */
    const ID_TYPE_STRING    = 1;
    const ID_TYPE_INT       = 2;

    /**
     * Triggers different getId() handling
     * @var int
     */
    protected $_idType = self::ID_TYPE_INT;


    /**
     * @var bool
     */
    protected $_cryptEnabled = false;


    /**
     * @var null|string
     */
    protected $_cryptSalt = null;

    /**
     * @var int ?
     */
    protected $_id;

    /**
     * @var string
     */
	public $class;

    /**
     * @var string
     */
    public $uid;



	/**
	 *
	 */
	public function __construct()
	{
		$this->class = str_replace("_", ".", $this->getClassName());
	}




	/**
	 * @return string
	 */
	public function getClassName()
	{
		return get_class($this);
	}

    /**
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function setDebugProperty($name, $value)
    {
        $key = "__debug__".$name;
        $this->$key = $value;        
    }

    /**
     * @param string $value
     * @return void
     */
    public function setId($value)
    {
        $this->_id = $value;


        $this->uid = $value;
        
        if ($this->isCryptEnabled() === true) {
            $salt = $this->getCryptSalt();
            $this->uid = App_Model_Dto_Utils_Crypt::getInstance()
                    ->encryptValue($value, $salt);
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setUID($value)
    {
        

        $this->uid = $value;

        $this->_id = $value;
        if ($this->isCryptEnabled() === true) {
            $salt = $this->getCryptSalt();
            $jsonAssoc = true;
            $id = App_Model_Dto_Utils_Crypt::getInstance()
                    ->decryptValue($value, $salt, $jsonAssoc);


            $this->_id = $id;
        }
    }

    /**
     * @return string|mixed|null
     */
    public function getUID()
    {
        return $this->uid;
    }

    /**
     * @return null|int
     */
    public function getId()
    {

        $value = $this->_id;

        if ($this->_idType == self::ID_TYPE_INT) {

            if (is_int($value)) {
                return $value;
            }
            
        } else {

            // we assume there are only the types ID_TYPE_INT|ID_TYPE_STRING

            if (is_string($value)) {
                return $value;
            }
        }
        return null;
    }


    /**
     * 
     * @return bool
     */
    public function hasValidId()
    {
        if ($this->_idType == self::ID_TYPE_INT) {

            $id = $this->getId();
            $id = Lib_Utils_TypeCast_String::asUnsignedInt($id, null);
            if ( (is_int($id)) && ($id>0) ) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("Implement in subclass "
                    . __METHOD__. " for ".get_class($this)
            );
        }
    }




    /**
     * @return string
     */
    public function getCryptSalt()
    {
        $salt = $this->_cryptSalt;

        if (is_string($salt) !== true) {
            $salt .= $this->getClassName();
        }
 
 
/*
        if (Bootstrap::getRegistry()->getViewer()->hasId()) {
            $salt .= Bootstrap::getRegistry()->getViewer()->getId();
        }
*/
        return $salt;
    }


    /**
     * @return bool
     */
    public function isCryptEnabled()
    {
        return (bool)($this->_cryptEnabled === true);
    }


    /**
     * @param  array $propertyNamesList
     * @return void
     */
    public function exposePropertiesExclusive($propertyNamesList)
    {
        $propertyNamesList = (array)$propertyNamesList;
        $vars = (array)get_object_vars($this);
        
        $this->unsetProperties(array_keys($vars));

        foreach ($vars as $key => $value) {
            if (in_array($key, $propertyNamesList, true)) {
                $this->$key = $value;
            }

        }

    }
    /**
     * @param  $propertyNamesList
     * @return void
     */
    public function unsetProperties($propertyNamesList)
    {
        if (is_array($propertyNamesList)!==true) {
            return;
        }
        foreach ($propertyNamesList as $property) {
            try {
                unset($this->$property);
            } catch (Exception $e) {
                //NOP
            }            
        }
    }
    /**
     * @return void
     */
    public function unsetPropertiesEmpty()
    {
        $vars = (array)get_object_vars($this);

        foreach ($vars as $key => $value) {
            switch($key) {
                default: {
                    if ($value === null) {
                        unset($this->$key);
                    }
                    break;
                }
            }
        }

    }

    /**
     * @return void
     */
    public function unsetPropertiesAll()
    {
        $vars = (array)get_object_vars($this);

        $this->unsetProperties(array_keys($vars));
    }




    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $value = null;

        try {
            $value = $this->$name;
        }catch(Exception $e) {
            //NOP
        }
        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

}
