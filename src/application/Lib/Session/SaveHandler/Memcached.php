<?php
/**
 * Lib_Session_SaveHandler_Memcached
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Session_SaveHandler
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Session_SaveHandler_Memcached
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Session_SaveHandler
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class Lib_Session_SaveHandler_Memcached
	implements Zend_Session_SaveHandler_Interface
{


    /**
     * @return string
     */
    public function getCacheIdPrefix()
    {
        $cacheIdPrefix = $this->getCache()->getOption('cache_id_prefix');

        if ((is_string($cacheIdPrefix)) && (empty($cacheIdPrefix)!==true)) {
            //NOP
        } else {
            $cacheIdPrefix = Bootstrap::getRegistry()->getApplicationPrefix();
            $cacheIdPrefix .= "__CLASS__".strtolower(get_class($this));
        }
        return $cacheIdPrefix;


    }

    /**
     * @param  string $id
     * @return string
     */
    public function newPrefixedId($id)
    {
        $cacheIdPrefix = $this->getCacheIdPrefix();
        $result = $cacheIdPrefix;
        $result .= "__ID__".$id;
        return $result;
    }

	

 	/**
      * is a fallback value
	 * @var int
	 */
	protected $_maxlifetime = 3600;

    /**
     * @var string
     */
    protected $_sessionSavePath;

    /**
     * @var string
     */
    protected $_sessionName;

	/**
	 * @var Zend_Cache_Core
	 */
	protected $_cache;



	/**
	 *
	 */
	public function __construct()
	{
	}



	/**
	 * @param Zend_Cache_Core $cache
	 * @return
	 */
	public function setCache(Zend_Cache_Core $cache)
	{
        $this->_cache = $cache;
	}

	



	/**
	 * @return Zend_Cache_Core
	 */
	public function getCache()
	{
		return $this->_cache;
	}




	/**
	 * @param  $savePath
	 * @param  $name
	 * @return bool
	 */
	public function open($savePath, $name)
	{
        //var_dump(__METHOD__);
        $this->_sessionSavePath = $savePath;
        $this->_sessionName     = $name;

        return true;
		
	}

	/**
	 * @return bool
	 */
	public function close()
	{
		return true;
	}




	/**
	 * @param  $id
	 * @return false|mixed|string
	 */
	public function read($id)
	{

        //$prefixedId = $id;
		$prefixedId = $this->newPrefixedId($id);


        $doNotTest = false;
        $doNotUnserialize = false;
        $data = $this->getCache()->load(
            $prefixedId,
            $doNotTest,
            $doNotUnserialize
        );
        if ($data === false) {
            return false;
        }

        return $data;

        /*
		if (!($data = $this->getCache()
			->load($prefixedId))) {
			return null;
		} else {
			return $data;
		}*/
	}

	/**
	 * @param  $id
	 * @param  $sessionData
	 * @return bool
	 */
	public function write($id, $sessionData)
	{
        //$prefixedId = $id;
        $prefixedId = $this->newPrefixedId($id);

        $maxLifeTime = (int)Zend_Session::getOptions('gc_maxlifetime');
        if ($maxLifeTime<1) {
            $maxLifeTime = $this->_maxlifetime;
        }

		$this->getCache()
			->save(
				$sessionData,
				$prefixedId,
				array(),
				$maxLifeTime
			);
		return true;
	}

	/**
	 * @param  $id
	 * @return bool
	 */
	public function destroy($id)
	{
        //$prefixedId = $id;
        $prefixedId = $this->newPrefixedId($id);
		$this->getCache()
			->remove($prefixedId);
		return true;
	}

	public function gc($notusedformemcache)
	{
		return true;
	}


    /**
     * @param  $id
     * @return false|int
     */
    public function test($id)
    {
        //$prefixedId = $id;
        $prefixedId = $this->newPrefixedId($id);
		return $this->getCache()->test($prefixedId);
    }

    /**
     * @param  $id
     * @param  $extraLifeTime
     * @return bool
     */
    public function touch($id, $extraLifeTime)
    {
        //$prefixedId = $id;
        $prefixedId = $this->newPrefixedId($id);
		return $this->getCache()->touch($prefixedId, $extraLifeTime);
               
    }


    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        Zend_Session::writeClose();
    }


}
