<?php
/**
 * Lib_Facebook_Client_CacheInvalidator
 *
 * @package		Lib_Facebook_Client
 * @category	basilicom
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_Facebook_Client_CacheInvalidator
 *
 * @package Lib_Facebook_Client
 *
 * @category	basilicom
 * @copyright	Copyright (c) 2010 basilicom GmbH (http://basilicom.de)
 * @license		http://basilicom.de/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Client_CacheInvalidator
{

    protected $_userId;

    protected $_graphId;

    protected $_additionalGraph ="*";

    protected $_graphParams = "*";


    public function setUserId($value)
    {
        // you may want to use '*'
        $this->_userId = $value;
    }
    public function getUserId()
    {
        return $this->_userId;
    }

    public function setGraphId($value)
    {
        // you may want to use '*'
        $this->_graphId = $value;
    }
    public function getGraphId()
    {
        return $this->_graphId;
    }


    public function setAdditionalGraph($value)
    {
        // you may want to use '*'
        $this->_additionalGraph = $value;
    }
    public function getAdditionalGraph()
    {
        return $this->_additionalGraph;
    }

    public function setGraphParams($value)
    {
        // you may want to use '*'
        $this->_graphParams = $value;
    }
    public function getGraphParams()
    {
        return $this->_graphParams;
    }


}
