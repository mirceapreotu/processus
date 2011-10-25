<?php

/**
 * Lib_JsonRpc_Reflector
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
 * Lib_JsonRpc_Reflector
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

class Lib_JsonRpc_Reflector
{


    const TYPE_API_ALL = "all";
    const TYPE_API_EXTJS = "extjs";
    const TYPE_API_FLASH = "flash";

    const TYPE_API_DEFAULT = self::TYPE_API_EXTJS;

     /**
     * @param Lib_JsonRpc_Server $server
     * @param  $destination
     * @param null $type
     * @param null $apiVersion
     * @return array
     */
    public function describeDestination(
        Lib_JsonRpc_Server $server,
        $destination,
        $type = null,
        $apiVersion=null)
    {
        

        $defaultType = self::TYPE_API_DEFAULT;

        if ($type===null) {
            $type = $defaultType;
        }

        switch($type)
        {
            case self::TYPE_API_EXTJS: {
                $api = $this->_describeDestination(
                    $server,
                    $destination,
                    $apiVersion
                );
                return $this->_formatApiResultExtJs($server, $api, $apiVersion);
                break;
            }
            case self::TYPE_API_FLASH: {
                $api = $this->_describeDestination(
                    $server,
                    $destination,
                    $apiVersion
                );
                return $this->_formatApiResultFlash($server, $api, $apiVersion);
                break;
            }
            case self::TYPE_API_ALL: {
                $api = $this->_describeDestination(
                    $server,
                    $destination,
                    $apiVersion
                );
                return $this->_formatApiResultAll($server, $api, $apiVersion);
                break;
            }
            default: {
                throw new Exception(
                    "Invalid value for parameter 'type' at ".__METHOD__
                );
            }
        }

        //$result = $this->_describeApi($server, $apiVersion);
        //return $result;


    }



    /**
     * @param Lib_JsonRpc_Server $server
     * @param null|string $type
     * @param null $apiVersion
     * @return array
     */
    public function describeApi(
        Lib_JsonRpc_Server $server,
        $type = null,
        $apiVersion=null
    )
    {
        $defaultType = self::TYPE_API_DEFAULT;


        if ($type===null) {
            $type = $defaultType;
        }

        switch($type)
        {

            case self::TYPE_API_EXTJS: {
                $api = $this->_describeApi($server, $apiVersion);
                return $this->_formatApiResultExtJs($server, $api, $apiVersion);
                break;
            }
            case self::TYPE_API_FLASH: {
                $api = $this->_describeApi($server, $apiVersion);
                return $this->_formatApiResultFlash($server, $api, $apiVersion);
                break;
            }
            case self::TYPE_API_ALL: {
                $api = $this->_describeApi($server, $apiVersion);
                return $this->_formatApiResultAll($server, $api, $apiVersion);
                break;
            }
            default: {
                throw new Exception(
                    "Invalid value for parameter 'type' at ".__METHOD__
                );
            }    
        }

        //$result = $this->_describeApi($server, $apiVersion);
        //return $result;
    }








    /**
     * @param Lib_JsonRpc_Server $server
     * @param null $apiVersion
     * @return array
     */
    protected function _formatApiResultAll(
        Lib_JsonRpc_Server $server,
        $api,
        $apiVersion=null
    )
    {
        $result = $api;
        return $result;
    }

    /**
     * @param Lib_JsonRpc_Server $server
     * @param array $api
     * @param null $apiVersion
     * @return array
     */
    protected function _formatApiResultFlash(
        Lib_JsonRpc_Server $server,
        $api,
        $apiVersion=null
    )
    {
        //$api = $this->_describeApi($server, $apiVersion);
        $result = array(
            "apiVersion" => $api["apiVersion"],
            "server" => $api["server"],
            "gateway" => $api["gateway"],
            "service" => array(),
        );



        $destinationDictionary = $api["destinationDictionary"];

        $dictionary = array();
        foreach($destinationDictionary as $key => $destinationInfo) {


            $item = array(

                "destination" =>
                    $destinationInfo["destinationParsed"]["destination"],
                "package" =>
                    $destinationInfo["destinationParsed"]["packageName"],
                "class" =>
                    $destinationInfo["destinationParsed"]["className"],
                "method" =>
                    $destinationInfo["destinationParsed"]["methodName"],
                "params" =>
                    $destinationInfo["params"],
                "returnType" =>
                    $destinationInfo["methodInfo"]["returnType"],
            );

            // try get advanced returnType info from annotations
            $returnType = null;
            try {
                $returnType =
                    $destinationInfo["methodInfo"]["docBlockTags"]
                        ["return"][0]["type"];

                $returnType = str_replace("_" ,".", $returnType);

            } catch(Exception $e) {

            }
            if (Lib_Utils_String::isEmpty($returnType)!==true) {
                $item["returnType"] = $returnType;
            }


            $dictionary[$key] = $item;
        }


        // build tree

        $serviceTree = $this->_newDestinationTreeFromDictionary(
            $dictionary
        );


        $result["service"] = $serviceTree;
        
        return $result;
    }


    /**
     * @param Lib_JsonRpc_Server $server
     * @param array $api
     * @param null $apiVersion
     * @return array
     */
    protected function _formatApiResultExtJs(
        Lib_JsonRpc_Server $server,
        $api,
        $apiVersion=null
    )
    {
        //$api = $this->_describeApi($server, $apiVersion);
        $result = array(
            "apiVersion" => $api["apiVersion"],
            "server" => $api["server"],
            "gateway" => $api["gateway"],
            "service" => array(),
        );



        $destinationDictionary = $api["destinationDictionary"];

        $tree = array();

        foreach($destinationDictionary as $key => $destinationInfo) {
            $classQualifiedName =
                    $destinationInfo["destinationParsed"]["classQualifiedName"];

            $item = array(

                "name" => $destinationInfo["destinationParsed"]["methodName"],
                "len" => (int)count($destinationInfo["params"]),
                "destination" =>
                    $destinationInfo["destinationParsed"]["destination"],
                "package" =>
                    $destinationInfo["destinationParsed"]["packageName"],
                "class" =>
                    $destinationInfo["destinationParsed"]["className"],
                "method" =>
                    $destinationInfo["destinationParsed"]["methodName"],
                "params" =>
                    $destinationInfo["params"],
                "returnType" =>
                    $destinationInfo["methodInfo"]["returnType"],
            );

            // try get advanced returnType info from annotations
            $returnType = null;
            try {
                $returnType =
                    $destinationInfo["methodInfo"]["docBlockTags"]
                        ["return"][0]["type"];

                $returnType = str_replace("_" ,".", $returnType);

            } catch(Exception $e) {

            }
            if (Lib_Utils_String::isEmpty($returnType)!==true) {
                $item["returnType"] = $returnType;
            }


            //$dictionary[$key] = $item;


            $mtree = Lib_Utils_String::splitRecursive($classQualifiedName, ".", array($item));
            $tree = array_merge_recursive($tree, $mtree);

        }


        $result["service"] = $tree;

        return $result;
    }




    /**
     * @param Lib_JsonRpc_Server $server
     * @param  string $destination
     * @param null $apiVersion
     * @return array
     */
    protected function _describeDestination(
        Lib_JsonRpc_Server $server,
        $destination,
        $apiVersion=null
    )
    {
        $api = $server->exploreServiceDestination($destination, $apiVersion);
        $result = $this->_describeApiExplored($server, $api);
        return $result;
    }

    /**
     * @param Lib_JsonRpc_Server $server
     * @param null $apiVersion
     * @return array
     */
    protected function _describeApi(
        Lib_JsonRpc_Server $server,
        $apiVersion=null
    )
    {
        $api = $server->exploreServiceDestinations($apiVersion);

        $result = $this->_describeApiExplored($server, $api);
        return $result;
    }







    /**
     * @param Lib_JsonRpc_Server $server
     * @param  array $api
     * @return
     */
    protected function _describeApiExplored(
        Lib_JsonRpc_Server $server,
        $api
    )
    {
        /*
        $result = array(
            "apiVersion" => (int)$apiVersion,
            "destination" => array(),
            "reflection" => array(),
            "destinationDictionary" => array(),
            "destinationTree" => array(),
        );

        */


       // $api = $server->exploreServiceDestinations($apiVersion);

        $result = $api;
        $result["destinationDictionary"]=array();
        $result["destinationTree"] = array();


        $destinations = $result["destination"];
        foreach($destinations as $destination)
        {



            $destinationParsed = $server->parseDestination($destination);

            $apiVersion = $result["apiVersion"];
            $serviceClassName = $server->findClassNameByDestinationParsed(
                $destinationParsed,
                $apiVersion
            );

            $serviceMethodName = $destinationParsed["methodName"];

            if (Lib_Utils_Class::exists($serviceClassName)!==true) {
                continue;
            }

            $serviceReflectionClass = new Zend_Reflection_Class(
                $serviceClassName
            );
            if ($serviceReflectionClass->hasMethod($serviceMethodName)!==true) {
                continue;
            }
            $serviceReflectionMethod = $serviceReflectionClass->getMethod(
                $serviceMethodName
            );


            $item = array(
                "destinationParsed" => $destinationParsed,
                "reflectionClass" => $serviceReflectionClass,
                "reflectionMethod" => $serviceReflectionMethod,
                "reflectionParams" => $serviceReflectionMethod->getParameters(),
                "method" => $destinationParsed["destination"],
                "params" => array(),
            );

            $item["classInfo"] = array(
                "name" => $serviceReflectionClass->getName(),
                "docComment" => (string)$serviceReflectionClass
                        ->getDocComment(),
                "docBlock" => null,
            );
            try {
                /*
                $item["classInfo"]["docBlock"] =
                    $serviceReflectionClass->getDocblock();
                */
            } catch(Exception $e) {
                //NOP
            }

            $item["methodInfo"] = array(
                "name" => $serviceReflectionMethod->getName(),
                "qualifiedName" => $serviceReflectionClass->getName()
                                   . "::"
                                   . $serviceReflectionMethod->getName(),
                "returnType" => null,
                "docComment" => (string)$serviceReflectionMethod
                        ->getDocComment(),
                "docBlock" => null,
                "docBlockTags" => array(),
                "paramsInfo" => array(
                    "total" =>
                        $serviceReflectionMethod->getNumberOfParameters(),
                    "required" => 0,
                    "optional" => 0,
                    "params" => array(),
                ),
            );
            try {
                $item["methodInfo"]["docBlock"] =
                        $serviceReflectionMethod->getDocblock();

                $tags = $serviceReflectionMethod->getDocblock()->getTags();
               
				$methodInfo["docBlockTags"] = array();
				foreach ($tags as $tag) {
                    /**
                     * @var Zend_Reflection_Docblock_Tag $tag
                     */

					if ($tag->getName()==="return") {
                        if (method_exists($tag, "getType")) {
                            $item["methodInfo"]["returnType"] = $tag->getType();
                        }
                    }

                    $methodInfoDocBlockTagInfo = array(
                        "name" => $tag->getName(),
						"description" => $tag->getDescription(),
                        "type" => null,
                        "variableName" => null,
                    );
                    if (method_exists($tag, "getType")) {
                        $methodInfoDocBlockTagInfo["type"] = $tag->getType();
                    }
                    if (method_exists($tag, "getVariableName")) {

                        $variableName = $tag->getVariableName();
                        $variableName = Lib_Utils_String::removePrefix(
                            $variableName, "$", true
                        );

                        $methodInfoDocBlockTagInfo["variableName"] =
                                $variableName;
                    }



                    //if ()
                    if ($tag->getName() === "param") {

                        $variableName = $tag->getVariableName();
                        $variableName = Lib_Utils_String::removePrefix(
                            $variableName, "$", true
                        );
                        $item["methodInfo"]["docBlockTags"]
                            [$tag->getName()][$variableName][] =
                                $methodInfoDocBlockTagInfo;



                    } else {
                        $item["methodInfo"]["docBlockTags"][$tag->getName()][]
                                = $methodInfoDocBlockTagInfo;
                    }



				}




            } catch(Exception $e) {
                //NOP
            }


            foreach ($serviceReflectionMethod->getParameters()
                as $reflectionParameter
            ) {

                /**
                 * @var Zend_Reflection_Parameter $reflectionParameter
                 */
					$parameterInfo = array(
						"name" => $reflectionParameter->getName(),
						"position" => $reflectionParameter->getPosition(),
						"isOptional" => $reflectionParameter->isOptional(),
						"isDefaultValueAvailable" =>
                            $reflectionParameter->isDefaultValueAvailable(),
						"allowsNull" => $reflectionParameter->allowsNull(),
						//"defaultValue" =>
                            //$reflectionParameter->getDefaultValue(),
						//"isArray" =>$reflectionParameter->isArray(),
                        "docBlockTags" =>array(),
					);

                    try {

                        $parameterInfo["docBlockTags"] =
                                $item["methodInfo"]["docBlockTags"]["param"]
                                    [$reflectionParameter->getName()];
                                        
                    }catch(Exception $e) {

                    }


					if ($reflectionParameter->isDefaultValueAvailable()) {
						$parameterInfo["defaultValue"] =
                                $reflectionParameter->getDefaultValue();
					}

					try {
						$parameterInfo["type"] =
                                $reflectionParameter->getType();
					} catch (Exception $e) {
						$parameterInfo["type"] = null;
					}


					$item["methodInfo"]["paramsInfo"]["params"][]
                            = $parameterInfo;
                    $item["params"][] = $reflectionParameter->getName();

				}

            if ($parameterInfo["isOptional"]===true) {
                $item["methodInfo"]["paramsInfo"]["optional"] ++;
            } else {
                $item["methodInfo"]["paramsInfo"]["required"] ++;
            }

            $item["methodInfo"]["paramsInfo"]["total"] =
                    $item["methodInfo"]["paramsInfo"]["required"]
                    +
                    $item["methodInfo"]["paramsInfo"]["optional"];


            $result["reflection"][]= $item;


            $destinationName = $destinationParsed["destination"];
            $result["destinationDictionary"][$destinationName] = (array)$item;

        }


        // build a tree

        $destinationDictionary = $result["destinationDictionary"];
        $destinationTree = $this->_newDestinationTreeFromDictionary(
            $destinationDictionary
        );
        $result["destinationTree"] = $destinationTree;

        return $result;
    }









    /**
     * @param  array $destinationDictionary
     * @return void
     */
    protected function _newDestinationTreeFromDictionary($destinationDictionary)
    {
        $tree = array();
        $destinations = (array)$destinationDictionary;
        $destinationNames = array_keys($destinations);
        foreach($destinationNames as $destinationName) {
            $destinationInfo = $destinations[$destinationName];
            $destinationTree = Lib_Utils_String::splitRecursive(
                $destinationName,
                ".",
                $destinationInfo,
                null);
            $tree = (array)array_merge_recursive($tree, $destinationTree);
        }
        return $tree;
    }




}
