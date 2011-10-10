<?php

/**
 * @EXPERIMENTAL
 * Lib_JsonRpc_Filter_ClassMethod
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
 * Lib_JsonRpc_Filter_ClassMethod
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
class Lib_JsonRpc_Filter_ClassMethod
    extends Lib_JsonRpc_Filter_AbstractFilter
{

      /**
     * @var array|null
     */
    protected $_whitelist;
    /**
     * @var array|null
     */
    protected $_blacklist;


    /**
     * @var string|null
     */
    protected $_qualifiedName;


    /**
     * @var array|null
     */
    protected $_parsedQualifiedName;


    /**
     * @return string|null
     */
    public function getQualifiedName()
    {
        return $this->_qualifiedName;
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
     * @return bool
     */
    public function isWhitelisted()
    {
        $value = $this->getQualifiedName();
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
        $value = $this->getQualifiedName();
        $list = $this->getBlacklist();
        $flags = null;

        $result = (bool)($this->match($value, $list, $flags) === true);
        return $result;
    }



    /**
     * @return array|null
     */
    public function getParsedQualifiedName()
    {
        if (is_array($this->_parsedQualifiedName) !== true) {
            $this->_parsedQualifiedName = $this->parse();
        }
        return $this->_parsedQualifiedName;
    }



    /**
     * @return string|null
     */
    public function getMethodName()
    {
        $parsed = $this->getParsedQualifiedName();
        return $parsed["methodName"];
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        $parsed = $this->getParsedQualifiedName();
        return $parsed["className"];
    }

    /**
     * @return string|null
     */
    public function getClassQualifiedName()
    {
        $parsed = $this->getParsedQualifiedName();
        return $parsed["classQualifiedName"];
    }

    /**
     * @return string|null
     */
    public function getPackageName()
    {
        $parsed = $this->getParsedQualifiedName();
        return $parsed["packageName"];
    }





    /**
     * @throws Exception
     * @param  string $qualifiedName
     * @return string
     */
    public function sanitize($qualifiedName)
    {
        $qualifiedName = trim($qualifiedName);
        if (is_string($qualifiedName) !== true) {
            throw new Exception("qualifiedName can't be empty");
        }
        if (strlen($qualifiedName)<1) {
            throw new Exception("qualifiedName can't be empty");
        }


        $allowChars = 'abcdefghijklmnopqrstuvwxyz0123456789:_';
		$allowChars = preg_quote($allowChars);
		$search = '/[^'.$allowChars.']/';
        $ignoreCase = true;
		if ($ignoreCase === true) {
			$search .= 'i';
		}
		$replace = '';
        $qualifiedNameFiltered = null;
        try {
            $qualifiedNameFiltered = preg_replace(
                $search,
                $replace,
                $qualifiedName
            );
        } catch (Exception $e) {
            // NOP
        }

        if (is_string($qualifiedNameFiltered) !== true) {
            throw new Exception("qualifiedName contains invalid chars");
        }

        /*
        if (strpos($qualifiedNameFiltered,"__") !== FALSE) {
            throw new Exception("qualifiedName contains invalid chars");
        }
         */

        if (strpos($qualifiedNameFiltered,":::") !== FALSE) {
            throw new Exception("qualifiedName contains invalid chars");
        }

        if (strlen(trim($qualifiedNameFiltered))<1) {
            throw new Exception("qualifiedName is empty");
        }
        if ($qualifiedNameFiltered !== $qualifiedName) {
            throw new Exception("qualifiedName contains invalid chars");
        }
        if (strlen($qualifiedNameFiltered) !== strlen($qualifiedName)) {
            throw new Exception("qualifiedName contains invalid chars");
        }

        $qualifiedNameFiltered = trim($qualifiedNameFiltered);

        if (Lib_Utils_String::startsWith($qualifiedNameFiltered,":")) {
            throw new Exception("qualifiedName must not start with a ':' char");
        }
        if (Lib_Utils_String::endsWith($qualifiedNameFiltered,":")) {
            throw new Exception("qualifiedName must not end with a ':' char");
        }


        $parts = explode("::", $qualifiedNameFiltered);
        if ((is_array($parts)) && (count($parts)>0)) {
            $method = array_pop($parts);

            $classQName = implode("::", (array)$parts);
            if (strpos($classQName, ":") !== FALSE) {
                throw new Exception("qualifiedName contains invalid chars");
            }
        }


        $this->_qualifiedName = $qualifiedNameFiltered;
        return $this->getQualifiedName();
    }





    /**
     * @return array
     */
    public function parse()
    {
        (string)$qualifiedName = $this->getQualifiedName();

        $result = array(
            "qualifiedName" => $qualifiedName,
            "classQualifiedName" => "",
            "className" => "",
            "methodName" => "",
            "packageName" => "",
        );

        if (is_string($qualifiedName)!==true) {
            return $result;
        }

        // methodName

        $result["methodName"] = Lib_Utils_String::getPostfixByDelimiter(
            $qualifiedName,
            "::"
        );

        $qualifiedName = Lib_Utils_String::removePostfixByDelimiterIfExists(
            $qualifiedName,
            "::"
        );

        $result["classQualifiedName"] = (string)$qualifiedName;

        $parts = explode("_", $qualifiedName);
        if (((is_array($parts)) && (count($parts)>0)) !== true) {
            $result["className"] = $result["classQualifiedName"];
            return $result;
        }

        // the class name
        $value = array_pop($parts);
        $result["className"] = "".$value;

        if (is_array($parts) !== true) {
            return $result;
        }

        $result["packageName"] = (string)implode("_" ,$parts);
        return $result;
    }



    /**
     * @param  string $className
     * @param string|null $methodName
     * @return null|string
     */
    public function newQualifiedName($className, $methodName = null)
    {
        $result = null;
        if (Lib_Utils_String::isEmpty($className)) {
            return $result;
        }

        $className = (string)trim($className);
        $result = $className;
        if (Lib_Utils_String::isEmpty($methodName)) {
            return $result;
        }
        $methodName = (string)trim($methodName);
        $result .= "::".$methodName;
        return $result;
    }

    
    
}


