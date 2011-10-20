<?php
/**
 * Lib_Template_StringParser Class
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
 * Lib_Template_StringParser
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


$p = new Lib_Template_StringParser();
$p->setTemplate("mein {foo.bar} {foo.bar.baz} {foo.bar} {foo.bar} {foo.x} text");
$p->setMarshallExceptions(false);
$p->setPattern("/\{([^\{\}]*)\}/i");
$p->setValueFunction(create_function(
                        '$value, $property, $marker',

                        '
                            //var_dump($value);
                            if ($value instanceof Exception) {
                                $value = "";
                                //$value .="ERROR_";
                                $value .= $marker;
                                return $value;
                            }

                            if ($value===null) {
                                $value = "EMPTY_";
                                $value .= $marker;
                                return $value;
                            }

                            if (is_string($value)) {
                                $value = htmlentities(
                                    $value, ENT_QUOTES, "UTF-8"
                                    );
                                return $value;
                            }

                            return $value;

                         '

                     ));
$data = array(

                                           "foo" => array(
                                               "bar" => array(
                                                   "baz" => "BAZZZ HelööWürld"
                                               )
                                           )


                                                   );

$r = $p->parse($data);
var_dump($r);




*/
class Lib_Template_StringParser
{


    protected $_delimiter = ".";//:String

    /**
     * @var string|null
     */
    protected $_pattern;//:RegExp;

    /**
     * @var string|null
     */
    protected $_template;//:String;

    /**
     * @var array|stdclass|object|mixed
     */
    protected $_data;//:Object;

    /**
     * @var bool
     */
    protected $_marshallExceptions = false;//:Boolean = false;

    /**
     * @var callback|null
     */
    protected $_valueFunction; //:Function


    /**
     * @return null|string
     */
    public function getPattern() //:RegExp
    {
        return $this->_pattern;
    }

    /**
     * @param  $value
     * @return void
     */
    public function setPattern($value)
    {
        if (((is_string($value))||($value===null))!==true) {
                throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_pattern = $value;
    }


    /**
     * @return null|string
     */
    public function getTemplate()//:String
    {
        return $this->_template;
    }

    /**
     * @throws Exception
     * @param  $value
     * @return void
     */
    public function setTemplate($value)
    {
        if (((is_string($value))||($value===null))!==true) {
                throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_template = $value;
    }


    /**
     * @return null|string
     */
    public function getDelimiter()//:String
    {
        if (is_string($this->_delimiter)!==true) {
            $this->_delimiter = ".";
        }

        return $this->_delimiter;
    }

    /**
     * @throws Exception
     * @param  $value
     * @return void
     */
    public function setDelimiter($value)
    {
        if ($value === null) {
            $this->_delimiter = $value;
            return;
        }

        if (is_string($value)!==true) {
            throw new Exception(
                "Invalid parameter 'delimiter' must be string/null at "
                .__METHOD__
            );
        }

        $this->_delimiter = $value;
    }




    /**
     * @return array|mixed|object|stdclass
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param  $value
     * @return void
     */
    public function setData($value) {
        $this->_data = $value;
    }

    /**
     * @throws Exception
     * @param  bool|null $value
     * @return void
     */
    public function setMarshallExceptions($value)
    {
        if (((is_bool($value))||($value===null))!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_marshallExceptions = $value;
    }

    /**
     * @return bool
     */
    public function getMarshallExceptions()
    {
        if ($this->_marshallExceptions !== false) {
            $this->_marshallExceptions = true;
        }
        return $this->_marshallExceptions;
    }

    /**
     * @throws Exception
     * @param  null|callback $value
     * @return
     */
    public function setValueFunction($value)
    {
        if ($value === null) {
            $this->_valueFunction = null;
            return;
        }

        if (is_callable($value)!==true) {
            throw new Exception(
                "Invalid parameter 'value' is not callable function at ".__METHOD__
            );
        }

        $this->_valueFunction = $value;

    }


    /**
     * @return callback|null
     */
    public function getValueFunction()
    {
        return $this->_valueFunction;
    }


    /**
     * @throws Exception
     * @param  array|stdclass|object|mixed|null|mixed $data
     * @return string
     */
    public function parse($data)
    {

        if ($data === null) {
            $data = $this->getData();
        }


        $marshallExceptions = $this->getMarshallExceptions();
        if ($marshallExceptions !== false) {
            $marshallExceptions = true;
            $this->_marshallExceptions = $marshallExceptions;
        }

        $valueFunction = $this->getValueFunction();
        if ($valueFunction !== null) {
            if (is_callable($valueFunction) !== true) {
                throw new Exception(
                    "Parser.valueFunction is not callable function at "
                    . __METHOD__
                );
            }
        }


        $template = $this->getTemplate();


        if ($template === null) {
            return "";
        }

        if ((is_string($template)) != true) {
            throw new Exception("No valid template set at " . __METHOD__);
        }


        $delimiter = $this->getDelimiter();
        if (is_string($delimiter)!==true) {
            throw new Exception("No valid delimiter set at ".__METHOD__);
        }


        if ($data === null) {
            $data = new stdclass();
        }
        $this->_data = $data;
        $data = $this->getData();

        if (Lib_Utils_String::isEmpty($this->getPattern())) {
            $this->_pattern = "/\{([^\{\}]*)\}/i";
        }

        $pattern = $this->getPattern();
        $subject = $template;

//$marshallExceptions=false;

        //var_dump($pattern);
        //var_dump($subject);

        $offset = 0;
        $match_count = 0;
        while (preg_match(
            $pattern,
            $subject,
            $matches,
            PREG_OFFSET_CAPTURE,
            $offset))
        {
            // Increment counter
            $match_count++;

            // Get byte offset and byte length (assuming single byte encoded)
            $match_start = $matches[0][1];
            $match_length = strlen($matches[0][0]);

            // (Optional) Transform $matches to the format it is usually set as (without PREG_OFFSET_CAPTURE set)
            $newmatches = null;
            foreach ($matches as $k => $match) {
                $newmatches[$k] = $match[0];
            }
            $matches = $newmatches;

            // Your code here
            //echo "Match number $match_count, at byte offset $match_start, $match_length bytes long: ".$matches[0]."\r\n";
            $match = $matches[0];

            $marker = $match;
            $property = $matches[1];
            //var_dump($match);

            $value = null;
            try {
                $value = $this->_getValue($data, $property, $delimiter);

                if (is_callable($valueFunction)) {
                    $valueFunctionArgs = array(
                        $value,
                        $property,
                        $marker
                    );
                    $value = call_user_func_array(
                        $valueFunction,
                        $valueFunctionArgs
                    );
                }


            } catch (Exception $e) {

                if ($marshallExceptions == true) {
                    $error = new Exception(
                        "Template Parser Error! marker="
                        . $marker . " " . $e->getMessage()."  ".__LINE__
                    );
                    throw $error;
                }



                if (is_callable($valueFunction)) {
                    //var_dump($e->getMessage());
                    try {
                        $valueFunctionArgs = array(
                            $e,
                            $property,
                            $marker
                        );
                        $value = call_user_func_array(
                            $valueFunction,
                            $valueFunctionArgs
                        );

                    }catch(Exception $userfuncError) {
                        throw new Exception("call valueFunction failed! at "
                                            .__METHOD__."details: "
                                            .$userfuncError->getMessage()."  ".__LINE__

                        );

                    }
                } else {
                   // var_dump($e->getMessage());exit;
                    $value = $match;
                }



            }

            $replace = null;
            try {
                $replace = "" . $value;
            } catch (Exception $e) {
                if ($marshallExceptions === true) {
                    $error = new Exception(
                        "Template Parser Error! marker="
                        . $marker . " " . $e->getMessage()
                    );
                    throw $error;
                }
                $replace = "" . $match;
            }


            if (((is_string($subject)) && (is_string($replace)))!==true) {
                throw new Exception("Invalid subject/replace at ".__METHOD__);
            }


            $subject = substr_replace(
                $subject,
                $replace,
                $match_start,
                $match_length
            );

            // Update offset to the end of the match
            //$offset = $match_start + $match_length;

            $offset = $match_start + strlen($replace);
        }





        //return $match_count;
        return "".$subject;


    }





    /**
     * @param  mixed $data
     * @param  string|mixed $property
     * @param string $delimiter
     * @return mixed
     */
    protected function _getValue($data, $property, $delimiter)
    {
        if ($data instanceof Exception) {

            $exception = $data;
            throw $exception;


            $result ="ERROR";
            return $result;
        }

        if (strlen($delimiter)>0) {
            $result = Lib_Utils_Object::getPropertyPublicRecursive(
                $data, $property, $delimiter, true, null
            );
        } else {
            $result = Lib_Utils_Object::getPropertyPublic(
                $data, $property, true, null
            );
        }

        return $result;
    }


}
