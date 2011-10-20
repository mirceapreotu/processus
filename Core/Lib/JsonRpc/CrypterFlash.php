<?php

/**
 * Lib_JsonRpc_CrypterFlash
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
 * Lib_JsonRpc_CrypterFlash
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
class Lib_JsonRpc_CrypterFlash
{

    /**
     * @var string
     */
    protected $_key = "you dont guess! 3i34hewnuqwhw";

    /**
     * @param  string $key
     * @return void
     */
    public function setKey($key)
    {
        $this->_key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }
    
     /**
     * @return array
     */
    protected function _getEncoderFixStrReplaceArgs()
    {
        // Das EURO-SYMBOL-Issue:
        // NOTE: THIS SCRIPT MUST BE UTF8-ENCODED OR WILL CRASH HERE!
        // IF YOU DONT SEE THE EURO-SYMBOL, FIX IT (CHECK YOU FILE ENCODING) !!!
        return array(
            "search" => "%u20AC",
            "replace" => "€"
        );
    }


    /**
     * @throws Lib_Application_Exception
     * @return array
     */
    public function decodeRequest()
    {
        # ======= GET REQUEST ======
        $data = null;
        $crypted = false;
        if (array_key_exists("jsonrpc",$_REQUEST))
        {
                $data = $_REQUEST["jsonrpc"];
        }
        # ======= HANDLE REQUEST ======
        if (((is_string($data)) && (strlen($data)>0))!==true)
        {
            // we don't have post.jsonrpc //

            $e = new Lib_Application_Exception(
                "Invalid Request (NO JSONRPC PROPERTY IN REQUEST)"
            );
            $e->setMethod(__METHOD__);
            throw $e;
        }
        // we have post.jsonrpc //
        $data = "".$data;
        //let's check we have plain json (object) data,
        // that has not been crypted
        $hasNonCryptedRpcData = (
            is_array (json_decode(stripslashes($data),true))
        );

        if ($hasNonCryptedRpcData === true)
        {
            $e = new Lib_Application_Exception(
                "Invalid Request (REQUEST MUST BE CRYPTED)"
            );
            $e->setMethod(__METHOD__);
            throw $e;
        }


        $data = Lib_Utils_CryptRC4::hex2data($data);
        $data = Lib_Utils_CryptRC4::rc4Encrypt(
            $this->getKey(),
            $data
        );
        /* +++ unescape (neu): die Variante ab AS3 +++ */
        $data = rawurldecode($data);
        $data = utf8_encode($data);
        // Das EURO-zeichen-Issue:
        // NOTE: THIS SCRIPT MUST BE UTF8-ENCODED OR WILL CRASH HERE!
        $strReplaceArgs = $this->_getEncoderFixStrReplaceArgs();
        //$data = str_replace("%u20AC","€", $data);
        $data = str_replace(
            $strReplaceArgs["search"],
            $strReplaceArgs["replace"],
            $data
        );
        //IF YOU DONT SEE AN EURO-SYMBOL HERE, CHECK YOUR ENCODING!!!
        /* +++ unescape (old): die AS2 Variante +++
            $data = CleanDecode($data);
         */

        $data = json_decode($data,true);

        if (is_array($data) !== true) {
            $e = new Lib_Application_Exception("DECRYPTED DATA IS INVALID");
            $e->setMethod(__METHOD__);
            throw $e;
        }

        // inject decrypted data //
        //$this->useData($data);
        return $data;
    }


    /**
     * @param  $data
     * @return int|string
     */
    public function encodeResponse($data)
    {
        /*
        if (is_string($data)!==true) {
			$e = new Lib_Application_Exception(
                        "INVALID RESPONSE. (DATA IS EMPTY)"
                    );
            $e->setMethod(__METHOD__);
            throw $e;
		}
        */
        $data = json_encode($data);
		$data = base64_encode($data);
		$data = Lib_Utils_CryptRC4::rc4Encrypt(
            $this->getKey(),
            $data
        );
		$data = Lib_Utils_CryptRC4::data2hex($data);

        $response = $data;
		return $response;
    }





    
}


