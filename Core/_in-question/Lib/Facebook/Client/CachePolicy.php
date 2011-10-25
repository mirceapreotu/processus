<?php
/**
 * Lib_Facebook_Client_CachePolicy
 *
 * @package		Lib_Facebook_Client
 * @category	basilicom
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_Facebook_Client_CachePolicy
 *
 * @package Lib_Facebook_Client
 *
 * @category	basilicom
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Client_CachePolicy
{

    /**
     * @var bool
     */
    protected $_enabled = true;

    /**
     * @var bool
     */
    protected $_load = true;

    /**
     * @var bool
     */
    protected $_save = true;


    /**
     * @var bool
     */
    protected $_invalidateOnError = true;


    protected $_ttl =null;

    protected $_ttlDefault = 120; // sec



    /**
     * @throws Exception
     * @param  bool|null $value
     * @return void
     */
    public function setLoad($value)
    {
        if ($value !== null) {
            if (is_bool($value)!==true) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }
        }
        $this->_load = $value;
    }
    /**
     * @return bool
     */
    public function getLoad()
    {
        return (bool)($this->_load === true);
    }

    /**
     * @throws Exception
     * @param  bool|null $value
     * @return void
     */
    public function setSave($value)
    {
        if ($value !== null) {
            if (is_bool($value)!==true) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }
        }
        $this->_save = $value;
    }
    /**
     * @return bool
     */
    public function getSave()
    {
        return (bool)($this->_save === true);
    }


    /**
     * @throws Exception
     * @param  bool|null $value
     * @return void
     */
    public function setEnabled($value)
    {
        if ($value !== null) {
            if (is_bool($value)!==true) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }
        }
        $this->_enabled = $value;
    }
    /**
     * @return bool
     */
    public function getEnabled()
    {
        return (bool)($this->_enabled !== false);
    }



    /**
     * @throws Exception
     * @param  bool|null $value
     * @return void
     */
    public function setInvalidateOnError($value)
    {
        if ($value !== null) {
            if (is_bool($value)!==true) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }
        }
        $this->_invalidateOnError = $value;
    }
    /**
     * @return bool
     */
    public function getInvalidateOnError()
    {
        return (bool)($this->_invalidateOnError === true);
    }




    /**
     * @throws Exception
     * @param  int|null $value
     * @return void
     */
    public function setTtl($value)
    {
        if ($value !== null) {
            if (is_int($value)!==true) {
                throw new Exception(
                    "Invalid parameter 'value' at ".__METHOD__
                );
            }
        }
        $this->_ttl = $value;
    }
    /**
     * @return int|null
     */
    public function getTtl()
    {
        return $this->_ttl;
    }


    /**
     * @return int
     */
    public function getTtlDefault()
    {
        return (int)$this->_ttlDefault;
    }



}
