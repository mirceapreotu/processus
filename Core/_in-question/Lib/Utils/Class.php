<?php


/**
 * Lib_Utils_Class
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_Class
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_Class
{

    /**
     * @param string|mixed $className
     * @return boolean
     */
    public static function exists($className)
    {
        
        $result = false;
        
        try {

            if (is_string($className) !== true) {
                return $result;
            }
            
            if ((strlen(trim($className)) > 0) !== true) {
                return false;
            }
            
            $result = (bool)class_exists($className);
            return $result;

        } catch (Exception $exception) {
            //NOP
        }
        
        return $result;
        
    }


	/**
	 * @TODO: TESTING!!!! NOT TESTED YET
	 * @static
	 * @throws Exception
	 * @param  object $instance
	 * @param  string $className
	 * @param array|null $args
	 * @return
	 */
	public static function ensureInstanceOf($instance,$className,$args = null)
	{
		if (is_string($className) !== true) {
			$message = "Parameter 'className' must be a string";
			$message .=" and cant be empty! className=".$className;
			$message .=" at method = ".__METHOD__;
			throw new Exception($message);
		}

		if ((strlen(trim($className)) > 0) !== true) {
			$message = "Parameter 'className' must be a string";
			$message .=" and cant be empty! className=".$className;
			$message .=" at method = ".__METHOD__;
			throw new Exception($message);
		}

		if (self::exists($className)!==true) {
			$message = "Parameter 'className' is invalid! ";
			$message .=" Class does not exists! className=".$className;
			$message .=" at method = ".__METHOD__;
			throw new Exception($message);
		}

		if (is_object($instance)===true) {
			if (is_subclass_of($instance, $className) === true) {
				return $instance;
			}
		}

		//factory

		$args = (array)$args;
		if ((count($args)>0)!==true) {
			$instance = new $className();
			return $instance;
		} else {
			$message = "Parameter 'args' is not supported yet! ";
			$message .=" at method = ".__METHOD__;
			throw new Exception($message);
		}



	}


    /**
     * @static
     * @param  $object
     * @param bool $useDotSyntax
     * @return null|string
     */
    public static function getClass($object, $useDotSyntax = false)
    {
        $result = null;
        try {
            $class = get_class($object);
            if ((is_string($class)) && ($useDotSyntax===true)) {
                $class = str_replace("_", ".", $class);
            }
            if (is_string($class)) {
                return $class;
            }
        } catch(Exception $e) {
            //NOP
        }

        return $result;
    }


    
}


