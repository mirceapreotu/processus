<?php
/**
 * Lib_Cache_MemcachedProxyInterface
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Cache
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Cache_MemcachedProxyInterface
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Cache
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
interface Lib_Cache_MemcachedProxyInterface
{


    /**
	 * each key will be prefixed
     * @return string
     */
    public function getNamespacePrefix();

    /**
     * @return array
     */
    public function getFrontendOptions();

    /**
     * @return array
     */
    public function getBackendOptions();

    /**
     * @return Zend_Core_Cache
     */
    public function getCache();


}
