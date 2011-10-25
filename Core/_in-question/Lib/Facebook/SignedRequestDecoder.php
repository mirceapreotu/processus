<?php
/**
 * Lib_Facebook_SignedRequestDecoder Class
 *
 * @package Lib_Facebook
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_SignedRequestDecoder
 *
 *
 * @package Lib_Facebook
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_SignedRequestDecoder
{

    /**
    * @var Lib_Facebook_Facebook
    */
    protected $_facebook;


    /**
     * @var array|null
     */
    protected $_requestData;


    /**
    * @param Lib_Facebook_Facebook $facebook
    */
    public function __construct(Lib_Facebook_Facebook $facebook)
    {
       $this->_facebook = $facebook;
    }


    /**
     * @return Lib_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->_facebook;
    }

    /**
     * @return array|null
     */
    public function getRequestData()
    {

        if (is_array($this->_requestData)) {
            return $this->_requestData;
        }

        $data = $this->decode($_REQUEST);
        $this->_requestData = $data;
        if ($this->_requestData === null) {
            return $this->_requestData;
        }
        


        $this->_requestData = $data;
        return $this->_requestData;

    }

    /**
     * @throws Exception
     * @param  array|null $data
     * @return void
     */
    public function setRequestData($data)
    {
        if (is_array($data)||($data===null)) {
            $this->_requestData = $data;
        }

        throw new Exception(
            "Parameter 'data' must be array/null at ".__METHOD__
        );
    }


    /**
     * @param  $request
     * @return array|null
     */
    public function decode($request)
    {

        $result = null;
        $signedRequest = Lib_Utils_Array::getProperty(
            $request,
            "signed_request"
        );
        if (is_string($signedRequest)!==true) {
            //var_dump(__LINE__);
            return $result;
        }

        try {
            $data = $this->getFacebook()->decodeSignedRequest($signedRequest);
            if (is_array($data)) {
                return $data;
            }
        } catch(Exception $e) {
            // NOP
            //var_dump($e);

        }

        return $result;
    }

    /**
     * @return array
     */
    public function getUser()
    {
        $data = $this->getRequestData();

        $user = array(
            "id" => null,
        );
        if (isset($data["user"])) {
            if (is_array($data["user"])) {
                $user = $data["user"];
            }
            if (isset($data["user_id"])) {
                $user["id"] = $data["user_id"];
            }
        }
        return $user;
    }

    /**
     * @return string|null
     */
    public function getUserId()
    {
        $user = $this->getUser();
        $userId = Lib_Utils_Array::getProperty($user, "user_id");
        if (is_string($userId)) {
            return $userId;
        }

        return null;
    }


    /**
     * @return array
     */
    public function getPage()
    {
        $page = array(
            "id" => null,
            "liked" => null,
            "admin" => null,
        );

        $data = $this->getRequestData();
                
        $_page = Lib_Utils_Array::getProperty($data, "page");
        if (is_array($_page)) {
            foreach($_page as $key => $value) {
                $page[$key] = $value;
            }
        }
        return $page;
    }


}
