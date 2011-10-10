<?php

/**
 * Lib_Utils_Config_Parser
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_Config
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_Config_Parser
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_Config
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_Config_Parser
{

    /**
     * @static
     * @param  Zend_Config|null $config
     * @return bool
     */
    public static function isEmpty($config)
    {
        $result = true;
        if ($config instanceof Zend_Config) {
            if ($config->count()>0) {
                return false;
            }
        }
        return $result;
    }

    /**
     * @static
     * @param  $key
     * @return Zend_Config|null
     */
    public static function loadByKey($key)
    {
        $config = Bootstrap::getRegistry()->getConfig()->$key;
        
        if (($config instanceof Zend_Config) !== true) {
            $config = null;
        }
        return $config;
    }


    /**
     * @static
     * @param  mixed $instanceOrClassname
     * @return Zend_Config|null
     */
    public static function loadByClass($instanceOrClassname)
    {
        $classname = $instanceOrClassname;
        if (is_object($instanceOrClassname)) {
            $classname = get_class($instanceOrClassname);
        }        
        return self::loadByKey($classname);
    }



    /**
     * @static
     * @param  mixed $instance
     * @param string|null $maxSuperClassname
     * @return null|Zend_Config
     */
    public static function loadByClassOrSuperClasses(
        $instance, $maxSuperClassname = null
    )
    {


        $config = self::loadByClass($instance);
        if ($config instanceof Zend_Config) {
            return $config;
        }

        $classname = null;
        if (is_object($instance)) {
            $classname = get_class($instance);
        }
        if (is_string($classname)!==true) {
            return null;
        }
        $maxLoops = 100;
        

        for ($i=0; $i<$maxLoops; $i++) {
            $classname = get_parent_class($classname);
            if (!empty($classname)) {
                $config = self::loadByClass($classname);
                if ($config instanceof Zend_Config) {
                    return $config;
                }    
            }

            if (strlen(trim($maxSuperClassname))>0) {
                if (strtolower(trim($classname))
                    === strtolower(trim($maxSuperClassname))) {
                    return null;
                }
            }
        }

        return null;
    }




    /**
     * each item in parameter 'list' must be instanceof Zend_Config or array
     * @static
     * @throws Exception
     * @param  $list
     * @return null|Zend_Config
     */
    public static function merge($list)
    {

        $result = null;
        if (is_array($list) !== true) {
            return $result;
        }

        if (count($list)<1) {
            return $result;
        }

        $result = null;
        foreach($list as $item) {
            if ($item === null) {
                continue;
            }

            if (is_array($item)) {

                $item = new Zend_Config($item, true);

            }

            if (($item instanceof Zend_Config) !== true) {
                throw new Exception(
                    "Invalid item in list at "
                    .__METHOD__
                    //." ".$item
                );
            }

            if ($result instanceof Zend_Config) {
                /**
                 * @var $result Zend_Config
                 */
                $result->merge($item);
            } else {
                $result = $item;
            }
        }

        return $result;
    }


    /**
     * @static
     * @param  Zend_Config|array|null $config
     * @return array
     */
    public static function toArray($config)
    {
        $result = array();
        if ($config instanceof Zend_Config) {
            /**
             * @var $config Zend_Config
             */
            return $config->toArray();
        }
        if (is_array($config)) {
            return $config;
        }

        try {
            return (array)$config;
        } catch(Exception $e) {
            // NOP
        }

        return $result;

    }


    /**
     * @static
     * @param  Zend_Config|array|null $config
     * @return Zend_Config
     */
    public static function toZendConfig($config)
    {
        if ($config instanceof Zend_Config) {
            return new Zend_Config($config->toArray(), true);
        }

        if ((is_array($config)) || ($config === null)) {
            return new Zend_Config((array)$config, true);
        }

        return new Zend_Config(array(), true);
    }



    /**
     * @static
     * @throws Exception
     * @param Zend_Config $source
     * @param Zend_Config $mixin
     * @param  array|null $fieldlist
     * @return Zend_Config
     */
    public static function toZendConfigAndMixinFields(
        Zend_Config $source,
        Zend_Config $mixin,
        $fieldlist
    )
    {
        if ((
                (is_array($source))
                || ($source === null)
                || ($source instanceof Zend_Config)
            ) !== true) {
            throw new Exception("Invalid parameter 'source' at ".__METHOD__);
        }
        if ((
                (is_array($mixin))
                || ($mixin === null)
                || ($mixin instanceof Zend_Config)
            ) !== true) {
            throw new Exception("Invalid parameter 'mixin' at ".__METHOD__);
        }


        $source = self::toZendConfig($source);
        $mixin = self::toZendConfig($mixin);



        if ($fieldlist !== null) {
            if (is_array($fieldlist) !== true) {
                throw new Exception(
                    "Invalid parameter 'fieldlist'"
                    ." must be array or null at ".__METHOD__
                );
            }
        }


        if (is_array($fieldlist)) {
            foreach($fieldlist as $fieldname) {
                if (property_exists($mixin, $fieldname)) {
                    $source->$fieldname = $mixin->$fieldname;
                }
            }
        } else {
            $source->merge($mixin);
        }

        return $source;
    }



}


