<?php

/**
 * Lib_JsonRpc_ServiceLocator
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_JsonRpc_ServiceLocator
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_JsonRpc_ServiceLocator
{



    /**
     * @var Lib_JsonRpc_Filter_ClassMethod
     */
    protected $_classMethodFilter;

    public function setClassMethodFilter($value)
    {
        $this->_classMethodFilter = $value;
    }

    public function getClassMethodFilter()
    {
        return $this->_classMethodFilter;
    }



    protected $_prefix;// = "App_JsonRpc_Service_Cms_";

    /**
     * @param  string $value
     * @return void
     */
    public function setPrefix($value)
    {
        $this->_prefix = $value;
    }

    /**
     * @return string|null
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * @var int|null
     */
    protected $_versionDefault = 1;

    /**
     * @var int|null
     */
    protected $_versionPreferred;

    /**
     * @param  int $value
     * @return void
     */
    public function setVersionPreferred($value)
    {
        $this->_versionPreferred = $value;
    }

    /**
     * @return int|null
     */
    public function getVersionPreferred()
    {
        return $this->_versionPreferred;
    }
    /**
     * @param  $value
     * @return void
     */
    public function setVersionDefault($value)
    {
        $this->_versionDefault = $value;
    }
    /**
     * @return int|null
     */
    public function getVersionDefault()
    {
        return $this->_versionDefault;
    }

    /**
     * @param  $destination
     * @return mixed|null|string
     */
    public function findServiceClassByDestination($destination)
    {

        $result = null;

        $destinationFilter = new Lib_JsonRpc_Filter_Destination();
        $destinationFilter->sanitize($destination);
        $destinationClassQName = $destinationFilter->getClassQualifiedName();
        $classQName = str_replace("." ,"_", $destinationClassQName);

        $preferredVersion = $this->getVersionPreferred();
        $minVersion = $this->getVersionDefault();

        if ($preferredVersion>$minVersion) {
            for ($i= $preferredVersion; $i>0; $i--) {
                $version = $i;

                $classNameFinal = $this->_newServiceClassName(
                    $classQName,
                    $version
                );
                if (class_exists($classNameFinal))  {
                    $reflectionClass = new ReflectionClass($classNameFinal);
                    $filterService = new Lib_JsonRpc_Filter_Service();
                    if ($filterService->isInstantiable($reflectionClass)) {
                        return $classNameFinal;
                    }
                }
            }
        }


        // find service by minversion
        $version = $minVersion;
        $classNameFinal = $this->_newServiceClassName(
            $classQName,
            $version
        );

        if (class_exists($classNameFinal)) {
            $reflectionClass = new ReflectionClass($classNameFinal);
            $filterService = new Lib_JsonRpc_Filter_Service();
            if ($filterService->isInstantiable($reflectionClass)) {
                return $classNameFinal;
            }


        }


        return $result;

    }
    

    public function removePrefixAndVersionFromClassName($classname)
    {
        $result = $classname;
        $prefix = $this->getPrefix();
        if (Lib_Utils_String::startsWith($result, $prefix, true)) {
            $result = Lib_Utils_String::removePrefix(
                $result,
                $prefix,
                true
            );

            $hasVersionPrefix = fnmatch(
                "[v][[:digit:]][_]*",
                $result,
                FNM_CASEFOLD
            );
            if ($hasVersionPrefix) {

                $result = Lib_Utils_String::removePrefixByDelimiterIfExists(
                    $result,
                    "_",
                    true
                );
                return $result;
            }

        }
        return $result;
    }


    public function getVersionFromClassname($classname)
    {
        $result = 0;
        $prefix = $this->getPrefix();
        if (Lib_Utils_String::startsWith($classname, $prefix, true)) {
            $classname = Lib_Utils_String::removePrefix(
                $classname,
                $prefix,
                true
            );

            $hasVersionPrefix = fnmatch(
                "[v][[:digit:]][_]*",
                $classname,
                FNM_CASEFOLD
            );
            if ($hasVersionPrefix) {

                $version = Lib_Utils_String::getPrefixByDelimiter(
                    $classname,
                    "_"
                );

                $version = (int)Lib_Utils_String::removePrefix($version,"V", true);
                
                return $version;
            }

        }
        return $result;
    }



    protected function _newServiceClassName($classQName, $version)
    {
        $version = (int)$version;



        $templateData = array(
            "prefix" => $this->getPrefix(),
            "version" => "V".$version."_",
            "rest" => $classQName,
        );

        if ($version>0) {
            return ""
                   . $templateData["prefix"]
                   . $templateData["version"]
                   . $templateData["rest"]
                    ;
        } else {
            return ""
                   . $templateData["prefix"]
                   //. $templateData["version"]
                   . $templateData["rest"]
                    ;
        }


        /*
        $s = $template;

        if ($version>0) {
            $template = "{prefix}{version}{rest}";
        } else {
            $template = "{prefix}{rest}";
        }
        foreach($templateData as $key => $value) {
            $s = str_replace("{".$key."}", $value, $s);
        }

        return $s;

         */
    }



    /**
     * @param  $className
     * @param null $methodName
     * @return string
     */
    public function newDestination($className, $methodName = null)
    {
        $classMethodFilter = new Lib_JsonRpc_Filter_ClassMethod();
        $qname = $classMethodFilter->newQualifiedName($className, $methodName);

        return $this->newDestinationByQualifiedName($qname);

    }

    /**
     * @param  $qualifiedName
     * @return mixed|string
     */
    public function newDestinationByQualifiedName($qualifiedName)
    {
        $classMethodFilter = new Lib_JsonRpc_Filter_ClassMethod();
       
        $classMethodFilter->sanitize($qualifiedName);

        $classQName = $classMethodFilter->getClassQualifiedName();
        $methodName = $classMethodFilter->getMethodName();

        $classQName = $this->removePrefixAndVersionFromClassName($classQName);
        $destination = $classQName.".".$methodName;
        $destination = str_replace("_", ".", $destination);
        return $destination;

    }



    

}


