<?php

/**
 * @EXPERIMENTAL
 * Lib_JsonRpc_Filter_Destination
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
 * Lib_JsonRpc_Filter_Destination
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
class Lib_JsonRpc_Filter_Destination
    extends Lib_JsonRpc_Filter_AbstractFilter
{

    /**
     * @var string|null
     */
    protected $_destination;
    /**
     * @var array|null
     */
    protected $_whitelist;
    /**
     * @var array|null
     */
    protected $_blacklist;


    /**
     * @var array|null
     */
    protected $_parsedDestination;




    /**
     * @return string|null
     */
    public function getDestination()
    {
        return $this->_destination;
    }







    /**
     * @return array|null
     */
    public function getParsedDestination()
    {
        if (is_array($this->_parsedDestination) !== true) {
            $this->_parsedDestination = $this->parse();
        }
        return $this->_parsedDestination;
    }

    /**
     * @return string|null
     */
    public function getMethodName()
    {
        $parsedDestination = $this->getParsedDestination();
        return $parsedDestination["methodName"];
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        $parsedDestination = $this->getParsedDestination();
        return $parsedDestination["className"];
    }

    /**
     * @return string|null
     */
    public function getClassQualifiedName()
    {
        $parsedDestination = $this->getParsedDestination();
        return $parsedDestination["classQualifiedName"];
    }

    /**
     * @return string|null
     */
    public function getPackageName()
    {
        $parsedDestination = $this->getParsedDestination();
        return $parsedDestination["packageName"];
    }


    /**
     * @throws Exception
     * @param  array|null $value
     * @return void
     */
    public function setWhitelist($value)
    {
        if ((((is_array($value)) || ($value === null)))!==true) {
            throw new Exception(
                "Parameter value must be array or null at ".__METHOD__
            );
        }
        $this->_whitelist = $value;
    }

    /**
     * @return array
     */
    public function getWhitelist()
    {
        if (is_array($this->_whitelist) !== true) {
            $this->_whitelist = array();
        }

        return $this->_whitelist;
    }



    /**
     * @throws Exception
     * @param  array|null $value
     * @return void
     */
    public function setBlacklist($value)
    {
        if ((((is_array($value)) || ($value === null)))!==true) {
            throw new Exception(
                "Parameter value must be array or null at ".__METHOD__
            );
        }
        $this->_blacklist = $value;
    }

    /**
     * @return array|null
     */
    public function getBlacklist()
    {
        if (is_array($this->_blacklist) !== true) {
            $this->_blacklist = array();
        }
        return $this->_blacklist;
    }


 

    /**
     * @param  array $items
     * @return
     */
    public function addToWhitelist($items)
    {
        $sourceList = $this->getWhitelist();
        $newList = $this->addToList($sourceList, $items);
        $this->setWhitelist($newList);
    }

     /**
     * @param  array $items
     * @return
     */
    public function removeFromWhitelist($items)
    {
        $sourceList = $this->getWhitelist();
        $newList = $this->removeFromList($sourceList, $items);
        $this->setWhitelist($newList);
    }

    /**
     * @param  $items
     * @return
     */
    public function addToBlacklist($items)
    {
        $sourceList = $this->getBlacklist();
        $newList = $this->addToList($sourceList, $items);
        $this->setBlacklist($newList);
    }

    /**
     * @param  $items
     * @return
     */
    public function removeFromBlacklist($items)
    {
        $sourceList = $this->getBlacklist();
        $newList = $this->removeFromList($sourceList, $items);
        $this->setBlacklist($newList);
    }
    





    /**
     * @throws Exception
     * @param  string $destination
     * @return
     */
    public function sanitize($destination)
    {
        $destination = trim($destination);
        if (is_string($destination) !== true) {
            throw new Exception("Destination can't be empty");
        }
        if (strlen($destination)<1) {
            throw new Exception("Destination can't be empty");
        }

        $allowChars = 'abcdefghijklmnopqrstuvwxyz0123456789.';
		$allowChars = preg_quote($allowChars);
		$search = '/[^'.$allowChars.']/';
        $ignoreCase = true;
		if ($ignoreCase === true) {
			$search .= 'i';
		}
		$replace = '';
        $destinationFiltered = null;
        try {
            $destinationFiltered = preg_replace(
                $search,
                $replace,
                $destination
            );
        } catch (Exception $e) {
            // NOP
        }

        if (is_string($destinationFiltered) !== true) {
            throw new Exception("Destination contains invalid chars");
        }

        if (strpos($destinationFiltered,"..") !== FALSE) {
            throw new Exception("Destination contains invalid chars");            
        }

        if (strlen(trim($destinationFiltered))<1) {
            throw new Exception("Destination is empty");
        }
        if ($destinationFiltered !== $destination) {
            throw new Exception("Destination contains invalid chars");
        }
        if (strlen($destinationFiltered) !== strlen($destination)) {
            throw new Exception("Destination contains invalid chars");
        }

        $destinationFiltered = trim($destinationFiltered);

        if (Lib_Utils_String::startsWith($destinationFiltered,".")) {
            throw new Exception("Destination must not start with a '.' char");
        }
        if (Lib_Utils_String::endsWith($destinationFiltered,".")) {
            throw new Exception("Destination must not end with a '.' char");
        }

        $this->_destination = $destinationFiltered;
        return $this->getDestination();
    }




    /**
     * @return bool
     */
    public function isWhitelisted()
    {
        $value = $this->getDestination();
        $list = $this->getWhitelist();
        $flags = null;

        $result = (bool)($this->match($value, $list, $flags) === true);
        return $result;
    }

    /**
     * @return bool
     */
    public function isBlacklisted()
    {
        $value = $this->getDestination();
        $list = $this->getBlacklist();
        $flags = null;

        $result = (bool)($this->match($value, $list, $flags) === true);
        return $result;
    }



    /**
     * @return array
     */
    public function parse()
    {
        $destination = (string)$this->getDestination();

        $result = array(
            "destination" => $destination,
            "className" => "",
            "classQualifiedName" => "",
            "methodName" => "",
            "packageName" => "",
            "rootPackageName" => "",
            "subPackageName" => "",
            "scope" => "",
        );

        if (is_string($destination)!==true) {
            return $result;
        }

        $parts = explode(".", $destination);
        if (((is_array($parts)) && (count($parts)>0)) !== true) {
            return $result;
        }

        // methodname
        $value = array_pop($parts);
        if ($value === null) {
            return $result;
        }
        $result["methodName"] = "".$value;

        if (((is_array($parts)) && (count($parts)>0)) !== true) {
            return $result;
        }
        
        // classname
        $value = array_pop($parts);
        if ($value === null) {
            return $result;
        }
        $result["className"] = "".$value;
        $result["classQualifiedName"] = $result["className"];

        if (((is_array($parts)) && (count($parts)>0)) !== true) {
            return $result;
        }

        // packagename
        $value = implode(".", $parts);
        if ($value === null) {
            return $result;
        }
        $result["packageName"] = "".$value;
        $result["classQualifiedName"] = $result["packageName"]
                                        . "."
                                        . $result["className"];

        // root package name / scope
        $packageParts = explode(".", $result["packageName"]);
        if (count($packageParts)>0) {
            $result["rootPackageName"] = (string)array_shift($packageParts);
            $result["subPackageName"] = (string)implode(".", $packageParts);

            $result["scope"] = (string)$result["rootPackageName"];
        }


        if (((is_array($parts)) && (count($parts)>0)) !== true) {
            return $result;
        }


        return $result;


    }

   


    /**
     * returns e.g.:
     *  "App.JsonRpc.Service.Cms.MyCmsPackage.MyCmsClass.myCmsMethod"
     * @param  string $phpClassMethodQualifiedName
     * @param null|string $phpClassPrefix
     * @return null|string
     */
    public function newDestination(
        $phpClassMethodQualifiedName,
        $phpClassPrefix = null
    )
    {
        $result = null;
        if (Lib_Utils_String::isEmpty($phpClassMethodQualifiedName)) {
            return $result;
        }

        $result = trim($phpClassMethodQualifiedName);

        if (Lib_Utils_String::isEmpty($phpClassPrefix) !== true) {
            // remove the prefix if exists
            $phpClassPrefix = trim($phpClassPrefix);
            $result = Lib_Utils_String::removePrefix(
                $result,
                $phpClassPrefix,
                true
            );
        }

        $result = str_replace("::", ".",$result);
        $result = str_replace("_",".", $result);

        if (is_string($result) !== true) {
            $result = null;
        }
        return $result;
    }


    

    /**
     * returns e.g.:
     *  "App_JsonRpc_Service_Cms_api_fb_service2::foo"
     * @param null|string $phpClassPrefix
     * @return null|string
     */
    /*
    public function toPhpClassMethodQualifiedName(
        $phpClassPrefix = null
    )
    {
        $result = null;

        $destination = $this->getDestination();
        if (Lib_Utils_String::isEmpty($destination)) {
            return $result;
        }

        $classQualifiedName = $this->getClassQualifiedName();
        if (Lib_Utils_String::isEmpty($classQualifiedName)) {
            return $result;
        }

        $classQNameTranslated = str_replace( ".", "_", $classQualifiedName);
        if (Lib_Utils_String::isEmpty($classQNameTranslated)) {
            return $result;
        }

        $result = "";

        if (Lib_Utils_String::isEmpty($phpClassPrefix) === true) {
            $result = $classQNameTranslated;
        } else {
            $phpClassPrefix = trim($phpClassPrefix);
            $result = "".$phpClassPrefix."".$classQNameTranslated;
        }

        
        $methodName = $this->getMethodName();
        if (Lib_Utils_String::isEmpty($methodName)!==true) {
            $methodName = trim($methodName);
            $result .= "::".$methodName;
        }

        return $result;

    }
    */
    
    
}


