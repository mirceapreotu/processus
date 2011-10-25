<?php
/**
 * Lib_Template_ArrayParser Class
 *
 * @EXPERIMENTAL
 * 
 * @package Lib_Template
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Template_ArrayParser
 *
 *
 * @package Lib_Template
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */


/*

-------------------- EXAMPLE USAGE ---------------

$template = array(
    "method"=> 'feed',
    "name"=> 'Facebook Dialogs',
    "link"=> 'http://developers.facebook.com/docs/reference/dialogs/',
    "picture"=> 'http://fbrell.com/f8.jpg',
    "caption"=> 'Reference {foo.bar.baz} Documentation',
    "description"=> 'Dialogs provide a simple, consistent interface .',
    "message"=> 'Facebook Dialogs are easy!' ,
);
$data = array(
    "foo" => array(
       "bar" => array(
           "baz" => "FOOO_BARR__BAZZZ 'Helöö'Würld and ".' "xyz" '
       )
    )
);


$p = new Lib_Template_ArrayParser();
$p->setTemplate($template);
$result=$p->parse($data);


var_dump($p->getTemplate());
var_dump($p->getData());
var_dump($result);



*/
class Lib_Template_ArrayParser
{

    /**
     * @var Lib_Template_StringParser
     */
    protected $_stringParser;


    /**
     * @var string|null
     */
    protected $_template;//:String;

    /**
     * @var array|mixed|null
     */
    protected $_data;//:String;




    /**
     * @return null|array
     */
    public function getTemplate()//:Array
    {
        return $this->_template;
    }

    /**
     * @throws Exception
     * @param  array|null $value
     * @return void
     */
    public function setTemplate($value)
    {
        if (((is_array($value))||($value===null))!==true) {
                throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_template = $value;
    }


    /**
     * @param  mixed|null $value
     * @return void
     */
    public function setData($value) {
        $this->_data = $value;
    }
    /**
     * @return array|mixed|null
     */
    public function getData() {
        return $this->_data;
    }





    /**
     * @var null|string
     */
    protected $_description;

    /**
     * @param  null|string $value
     * @return void
     */
    public function setDescription($value) {
        $this->_description = $value;
    }

    /**
     * @return null|string
     */
    public function getDescription() {
        return $this->_description;
    }


    /**
     * @return void
     */
    public function resetConfig() {
        $this->setTemplate(null);
        $this->setData(null);
        $this->setDescription(null);
    }
    /**
     * @throws Exception
     * @param  array|Zend_Config|null $value
     * @return
     */
    public function applyConfig($value)
    {
        if ($value === null) {
            $this->resetConfig();
            return;
        }
        if ($value instanceof Zend_Config) {
            /**
             * @var Zend_Config $value
             */
            $value = $value->toArray();
        }

        if (is_array($value)!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }

        $config = array(
            "template" => Lib_Utils_Array::getProperty($value, "template"),
            "data" => Lib_Utils_Array::getProperty($value, "data"),
            "description" => Lib_Utils_Array::getProperty(
                $value, "description"),

        );

        $this->setTemplate($config["template"]);
        $this->setData($config["data"]);
        $this->setDescription($config["description"]);
    }
    /**
     * @return array
     */
    public function getConfig() {
        $result = array(
            "class" => str_replace("_", ".", get_class($this)),
            "template" => $this->getTemplate(),
            "data" => $this->getData(),
            "description" => $this->getDescription(),
        );
        return $result;
    }














    /**
     * @return Lib_Template_StringParser
     */
    public function getStringParser()
    {
        if (($this->_stringParser instanceof Lib_Template_StringParser)
            !==true) {
            $this->_stringParser = $this->newStringParser();
        }

        if (($this->_stringParser instanceof Lib_Template_StringParser)!==true) {
            throw new Exception("method returns invalid result at ".__METHOD__);
        }

        return $this->_stringParser;
    }

    /**
     * @throws Exception
     * @param  array|null $value
     * @return void
     */
    public function setStringParser($value)
    {
        if ($value===null) {
            $this->_stringParser = null;
            return;
        }
        if (($value instanceof Lib_Template_StringParser)!==true) {
                throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_stringParser = $value;
    }


   /**
    * @return Lib_Template_StringParser
    */
    public function newStringParser()
    {
        $stringParser = new Lib_Template_StringParser();
        return $stringParser;
    }




    protected $_marshallExceptions = true;
    public function setMarshallExceptions($value) {
        if ($value !== false) {
            $value = true;
        }
        $this->_marshallExceptions = $value;
    }

    public function getMarshallException() {
        $value = $this->_marshallExceptions;
        if ($value !== false) {
            $value = true;
        }
        return $value;
    }



    public function parse($data) {
        try {
            return $this->_parse($data);
        }catch(Exception $e) {
            throw $e;
        }
    }


    /**
     * @throws Exception
     * @param  array|stdclass|object|mixed|null|mixed $data
     * @return array|null
     */
    protected function _parse($data)
    {

        $result = null;
        $template = $this->getTemplate();
        if ($template === null) {
            return $result;
        }
        if (is_array($template)!==true) {
            throw new Exception("Invalid property 'template' at ".__METHOD__);
        }

        $stringParser = $this->getStringParser();
        $stringParser->setMarshallExceptions($this->getMarshallException());

        if ($data === null) {
            $data = $this->getData();
        }
        $this->setData($data);

        //$stringParser->setTemplate($this->getTemplate());
        $stringParser->setData($this->getData());


        $callback = array($this,"_itemFunction");
        if (is_callable($callback)!==true) {
            throw new Exception("Invalid 'callback' at ".__METHOD__);
        }
        $callbackCustomArgs = array();

        $templateParsed = $this->_walkArray(
            $template, $callback, $callbackCustomArgs, 1000, 0
        );

        $templateCloned = $this->_cloneArray($templateParsed);


        return $templateCloned;



    }




    protected function _walkArray(
                $array,
        $callback, $callbackCustomArgs,
        $maxIterations, $currentIteration ) {

        if (is_array($array)!==true) {
            return;
        }

        if (is_callable($callback)!==true) {
            throw new Exception("Invalid parameter 'callback' at ".__METHOD__);
        }

        if ($maxIterations === null) {
            $maxIterations = 1000;
        }
        if (is_int($maxIterations)!==true) {
            throw new Exception("Invalid parameter 'maxIterations' at "
                                .__METHOD__);
        }
        if ($maxIterations<1) {
            throw new Exception("Invalid parameter 'maxIterations' at "
                                .__METHOD__);
        }

        if (is_int($currentIteration)!==true) {
            throw new Exception("Invalid parameter 'currentIteration' at "
                                .__METHOD__);
        }

        if ($currentIteration>$maxIterations) {
            throw new Exception("max recursion limit exceeded  at "
                                .__METHOD__);

        }


        if ($callbackCustomArgs === null) {
            $callbackCustomArgs = array();
        }
        if (is_array($callbackCustomArgs)!==true) {
            throw new Exception(
                "Invalid parameter 'callbackCustomArgs' at ".__METHOD__
            );
        }

        foreach($array as $key => $value) {



            if (is_array($value)) {
                $currentIteration++;
                $newValue = $this->_walkArray(
                    $value,
                    $callback,
                    $callbackCustomArgs,
                    $maxIterations, $currentIteration
                );
            } else {
                $callbackArgs = array(
                    $array,
                    $key,
                    $value,
                    $callbackCustomArgs,
                );
                $newValue = call_user_func_array($callback, $callbackArgs);

            }



            $array[$key] = $newValue;


        }

        return $array;

    }


    protected function _itemFunction($array, $key, $value, $customArgs) {

        if (is_string($value)!==true) {
            return $value;
        }

        $stringTemplateParser = $this->getStringParser();
//        $stringTemplateParser->setMarshallExceptions(false);

        $stringTemplateParser->setTemplate($value);

        $parsedValue=null;

        $stringTemplateParser->setData($this->getData());
        $parsedValue = $stringTemplateParser->parse(null);

        $newValue = $parsedValue;




        return $newValue;
    }



    /**
     * @param  array|mixed $input
     * @return array
     */
    protected function _cloneArray($input)
    {
        if (!is_array($input)) return $input;


        $output = array();
        foreach ($input as $key => $value) {
            $output[$key] = $this->_cloneArray($value);
        }
        return $output;
    }

}
