<?php
/**
 * Lib_Application_Exception
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Application
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Application_Exception
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Application
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class Lib_Application_Exception extends Exception
{


    /**
     * @var null|string
     */
    protected $_message;

    /**
     * @var int|null
     */
    protected $_methodLine;

	/**
	 * @var string|null
	 */
	protected $_scope;

	/**
	 * @var string|null
	 */
	protected $_userMessage;
	/**
	 * @var string|null
	 */
	protected $_method;
	/**
	 * @var array
	 */
	protected $_data;

	/**
	 * @var array|null
	 */
	protected $_fault;



    /**
     * @param string|null $message
     * @param null $code
     * @param null $previous
     * 
     */
    public function __construct($message = null, $code = null, $previous = null)
    {
        // edit xris: "previous erst ab php 5.3!
        parent::__construct($message, $code);
        $this->_message = $message;
    }


    /**
     * @param  string $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->_message = "".$message;
    }

    
    /**
    * @param  int $line
    * @return void
    */
    public function setMethodLine($line)
    {
       $this->_methodLine = (int)$line;
    }

    /**
     * override
     * @return int
     */

    public function getMethodLine()
    {
        return (int)$this->_methodLine;
    }

	/**
	 * @param  string|null $message
	 * @return Lib_Application_Exception
	 */
	public function setUserMessage($message)
	{
		$result = $this;

		if ($message === null) {
			$this->_userMessage = null;
			return $result;
		}

		if (is_string($message) !== true) {
			$this->_userMessage = null;
			return $result;
		}

		$this->_userMessage = "".$message;
		return $result;
	}

	/**
	 * @return null|string
	 */
	public function getUserMessage()
	{
		if ($this->_userMessage === null) {
			return null;
		}
		return "".$this->_userMessage;
	}



	/**
	 * @return string
	 */
	public function getScope()
	{
		if ($this->_scope === null) {
			return null;
		}
		return "".$this->_scope;
	}

	public function setScope($value)
	{
		if (is_string($value)!==true) {
			$value = null;
		}
		$this->_scope = $value;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getMethod()
	{
		if ($this->_method === null) {
			return null;
		}
		return "".$this->_method;
	}

	/**
	 * @param  string|null $value
	 * @return Lib_Application_Exception
	 */
	public function setMethod($value)
	{
		if (is_string($value)!==true) {
			$value = null;
		}
		$this->_method = $value;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getData()
	{
		return (array)$this->_data;
	}

	/**
	 * @param  array|null $value
	 * @return Lib_Application_Exception
	 */
	public function setData($value)
	{
		if ($value !== null) {
			$value = (array)$value;
		}
		$this->_data = $value;
		return $this;
	}


	/**
	 * @param  array|Exception|null $fault
	 * @return Lib_Application_Exception
	 */
	public function setFault($fault)
	{
		$result = $this;
		$value = null;
		if ($fault === null) {
			$this->_fault = null;
			return $result;
		}
		if ($fault instanceof Exception) {

			if ($fault instanceof Lib_Application_Exception) {
                /** @noinspection PhpUndefinedMethodInspection */
                $value = $fault->export();
			} else {
				$value = Lib_Utils_Exception::export($fault);
			}
			$this->_fault = $value;
			return $result;
		}

		$value = (array)$fault;

		$this->_fault = $value;
		return $result;
	}



	/**
	 * @return array|null
	 */
	public function getFault()
	{
		if ($this->_fault === null) {
			return null;
		}
		return (array)$this->_fault;
	}


	/**
	 * @return array
	 */
	public function toArray()
	{

		$result = (array)Lib_Utils_Exception::toArray($this);
		$mixIn = array(
			"scope" => $this->getScope(),
			"method" => $this->getMethod(),
			"userMessage" => $this->getUserMessage(),
			"data" => $this->getData(),
			"fault" => $this->getFault(),
		);
		$result = (array)array_merge($result, $mixIn);

		foreach ($result as $key => $value) {
			if ($value === null) {
				unset($result[$key]);
			}
		}

		return $result;
	}


	/**
	 * @return array|null
	 */
	public function export()
	{
		$result = null;
		$array = $this->toArray();
		if (is_array($array)!==true) {
			return $result;
		}

		$numKeys = (int)count(array_keys($array));
		if (($numKeys>0)!==true) {
			return $result;
		}

		return $array;
	}


	public function toJson()
	{
		return json_encode($this->export());
	}

	/**
	 * METHOD_NOT_FINAL_IMPLEMENTED_YET!
	 * @static
	 * @throws Exception
	 * @param  $fault
	 * @return
	 */
	public static function newInstanceFromFault($fault)
	{
		throw new Exception("METHOD_NOT_FINAL_IMPLEMENTED_YET! ".__METHOD__);
		$message = Lib_Utils_Exception::getMessage($fault);
		$code = Lib_Utils_Exception::getCode($fault);
		$previous = Lib_Utils_Exception::getPrevious($fault);

		$class = get_class(self);
		$instance = new $class($message,$code,$previous);

		return $instance;

	}





}
