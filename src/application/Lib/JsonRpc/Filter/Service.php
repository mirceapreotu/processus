<?php

/**
 * @EXPERIMENTAL
 * Lib_JsonRpc_Filter_Service
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc_Filter
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * @EXPERIMENTAL
 * Lib_JsonRpc_Filter_Service
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc_Filter
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_JsonRpc_Filter_Service
    extends Lib_JsonRpc_Filter_AbstractFilter
{


    protected $_requiredInterfaceNames = array(
        "Lib_JsonRpc_ServiceInterface",
    );

    /**
	 * define what Interfaces a Service must implement
	 * @return array|null
	 */
	public function getRequiredInterfaceNames()
	{
		return $this->_requiredInterfaceNames;
	}

    public function setRequiredInterfaceNames($interfaceNames)
    {
        if ((is_array($interfaceNames)) || ($interfaceNames===null)) {
            //ok
        } else {
            throw new Exception(
                "Parameter interfaceNames must be array or null at ".__METHOD__
            );
        }

        $this->_requiredInterfaceNames = $interfaceNames;
    }




    /**
     * @param ReflectionClass $reflectionClass
     * @return void
     */
    public function listReflectionMethodsInvokable(
        ReflectionClass $reflectionClass
    )
    {

        $result = array();

        $methods = $reflectionClass->getMethods();
        foreach($methods as $reflectionMethod) {
            if (
                    $this->isReflectionMethodInvokeable($reflectionMethod)
                !== true) {
                continue;
            }
            $result[] = $reflectionMethod;
        }

        return $result;
    }

    /**
	 *
	 * @param ReflectionClass $reflectionClass
	 * @return boolean
	 */
	public function isInstantiable(
		ReflectionClass $reflectionClass
	)
	{
        $result = false;


		try {

            $requiredInterfaces = $this->getRequiredInterfaceNames();

            $className = $reflectionClass->getName();

            if (strpos($className, "__") !== FALSE) {
                return $result;
            }

			if ($reflectionClass->isAbstract() === true) {
				return $result;
			}
			if ($reflectionClass->isInterface() === true) {
				return $result;
			}
			if ($reflectionClass->isInternal() === true) {
				return $result;
			}
			if ($reflectionClass->isInstantiable() !== true) {
				return $result;
			}

			if ($this->implementsInterfaces(
                    $reflectionClass,
                    $requiredInterfaces
                ) !== true) {
                return $result;
            }


			return true;

		} catch (Exception $exception) {
            // NOP
		}

		return $result;
	}


    

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return void
     */
    public function isReflectionMethodInvokeable(
                ReflectionMethod $reflectionMethod
    )
    {

        $result = false;
        try {

            $methodName = $reflectionMethod->getName();
            if (strpos($methodName, "_") !== FALSE) {
                return $result;
            }
			
			if ($reflectionMethod->isPublic() !== true) {
				return $result;
			}
			if ($reflectionMethod->isAbstract() === true) {
				return $result;
			}
			if ($reflectionMethod->isStatic() === true) {
				return $result;
			}


            if (
				Lib_Utils_String::startsWith(
					$methodName,
					"_", true
				)
			) {
				return false;
			}


			return true;

		} catch (Exception $exception) {
			//NOP
		}
		return $result;
    }

    



    /**
     * @param ReflectionClass $reflectionClass
     * @param  string|array $interfaceNames
     * @return bool
     */
    public function implementsInterfaces(
        ReflectionClass $reflectionClass,
        $interfaceNames
    )
    {
        $result = false;

        $interfaces = array();
        if (is_array($interfaceNames) !== true) {
            $interfaces[] = $interfaceNames;
        }

        foreach($interfaces as $interfaceName) {

            if (Lib_Utils_String::isEmpty($interfaceName)) {
                continue;
            }

            $interfaceName = trim($interfaceName);
            $hasInterface = false;

            try {
                $hasInterface = $reflectionClass
                        ->implementsInterface($interfaceName);
            } catch (Exception $e) {
                //NOP
            }

            if ($hasInterface !== true) {
                return $result;
            }

        }

        return true;

    }



    
}


