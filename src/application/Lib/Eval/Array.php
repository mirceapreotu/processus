<?php
/**
 * @EXPERIMENTAL
 * Lib_Eval_Array Class
 *
 * @package Lib_Eval
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * @EXPERIMENTAL
 * Lib_Eval_Array
 *
 *
 * @package Lib_Eval
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Eval_Array
{

    //@see: rockmongo

    /**
	 * Source to run
	 *
	 * @var string
	 */
	private $_source;



    /**
     * @param null $source
     */
	public function __construct($source = null) {
		$this->_source = $source;
	}

    /**
     * @param  string|null $value
     * @return void
     */
    public function setSource($value)
    {
        $this->_source = $value;
    }

	/**
	 * execute the code
	 *
	 * @return mixed
	 */
	function execute() {

        return $this->_runPHP();
	}

	private function _runPHP() {
		$source = "return " . $this->_source . ";";
		    //tokenizer extension may be disabled
        if (function_exists("token_get_all")!==true) {
            throw new Exception("Function does not exist. token_get_all() ");
            //return eval($source);
        }

        // validate source
        $php = "<?php\n" . $source . "\n?>";
        $tokens = token_get_all($php);
        foreach ($tokens as $token) {
            $type = $token[0];
            if (is_long($type)) {
                if (in_array($type, array(
                                         T_OPEN_TAG,
                                         T_RETURN,
                                         T_WHITESPACE,
                                         T_ARRAY,
                                         T_LNUMBER,
                                         T_DNUMBER,
                                         T_CONSTANT_ENCAPSED_STRING,
                                         T_DOUBLE_ARROW,
                                         T_CLOSE_TAG,
                                         T_NEW))
                ) {
                    continue;
                }

                if ($type == T_STRING) {
                    $func = strtolower($token[1]);
                    if (in_array($func, array(
                                             //keywords allowed
                                             /*
                                             "mongoid",
                                             "mongocode",
                                             "mongodate",
                                             "mongoregex",
                                             "mongobindata",
                                             "mongoint32",
                                             "mongoint64",
                                             "mongodbref",
                                             "mongominkey",
                                             "mongomaxkey",
                                             "mongotimestamp",
                                             */
                                             "true",
                                             "false",
                                             "null"
                                        ))
                    ) {
                        continue;
                    }
                }

                throw new Exception(
                    "Security error! "."invalid token while parsing at '("
                        . token_name($type) . ") " . $token[1] . "'."
                );

            }
        }

        // execute source
        return eval($source);


    }


    // ++++++++++++++++++++++++ export +++++++++++++++++++++++++++++

    /**
     * @param  $var
     * @return mixed
     */
	public function export($var) {

        return $this->_exportPhp($var);
	}

    /**
     * @param  $var
     * @return mixed
     */
	private function _exportPhp($var) {
		$var = $this->_formatVar($var);
		$string = var_export($var, true);
		return $string;
	}

    /**
	 * Enter description here...
	 *
	 * @param unknown_type $str
	 * @param unknown_type $from
	 * @param unknown_type $len
	 * @return unknown
	 * @author sajjad at sajjad dot biz (copied from PHP manual)
	 */
	private function _utf8_substr($str,$from,$len) {
        
		return function_exists('mb_substr') ?
            mb_substr($str, $from, $len, 'UTF-8') :
		    preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'
                         . $from .'}'
                         .'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'
                         . $len .'}).*#s','$1', $str);
	}




    /**
     * @throws Exception
     * @param  $var
     * @return array
     */
    private function _formatVar($var) {
		if (is_scalar($var) || is_null($var)) {
			return $var;
		}
		if (is_array($var)) {
			foreach ($var as $index => $value) {
				$var[$index] = $this->_formatVar($value);
			}
			return $var;
		}

		if (is_object($var)) {

            switch(get_class($var)) {
                case "stdClass": {
                    $var = (array)$var;
                    foreach ($var as $index => $value) {
                        $var[$index] = $this->_formatVar($value);
                    }

                    return $var;
                    break;
                }
                default:

                    throw new Exception(
                        " instanceof ".get_class($var)." can not be exported!"
                    );
                break;
            }

            /*
			$this->_paramIndex ++;
			switch (get_class($var)) {
				case "MongoId":
					$this->_phpParams[$this->_paramIndex] = 'new MongoId("' . $var->__toString() . '")';
					return $this->_param($this->_paramIndex);
				case "MongoDate":
					$this->_phpParams[$this->_paramIndex] = 'new MongoDate(' . $var->sec . ', ' . $var->usec . ')';
					return $this->_param($this->_paramIndex);
				case "MongoRegex":
					$this->_phpParams[$this->_paramIndex] = 'new MongoRegex(\'/' . $var->regex . '/' . $var->flags . '\')';
					return $this->_param($this->_paramIndex);
				case "MongoTimestamp":
					$this->_phpParams[$this->_paramIndex] = 'new MongoTimestamp(' . $var->sec . ', ' . $var->inc . ')';
					return $this->_param($this->_paramIndex);
				case "MongoMinKey":
					$this->_phpParams[$this->_paramIndex] = 'new MongoMinKey()';
					return $this->_param($this->_paramIndex);
				case "MongoMaxKey":
					$this->_phpParams[$this->_paramIndex] = 'new MongoMaxKey()';
					return $this->_param($this->_paramIndex);
				case "MongoCode":
					$this->_phpParams[$this->_paramIndex] = 'new MongoCode("' . addcslashes($var->code, '"') . '", ' . var_export($var->scope, true) . ')';
					return $this->_param($this->_paramIndex);
				default:
					if (method_exists($var, "__toString")) {
						return $var->__toString();
					}
			}*/
		}

		return $var;
	}
	
}