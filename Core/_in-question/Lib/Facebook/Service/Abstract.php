<?php
/**
 * Lib_Facebook_Service_Abstract Class
 *
 * @package Lib_Facebook_Service
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Service_Abstract
 *
 *
 * @package Lib_Facebook_Service
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Service_Abstract
{

    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }

    /**
     * @return App_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }


    /**
     * @param  int|string|null|mixed $id
     * @return bool
     */
    public function isValidId($id)
    {
        return $this->getFacebook()->isValidId($id);
    }


    /**
     * @throws Lib_Application_Exception
     * @param  mixed $id
     * @param  string|null $errorMethod
     * @return void
     */
    public function requireValidGraphId($id, $allowMe, $errorMethod)
    {
        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }

        if ($allowMe === null) {
            $allowMe = false;
        }

        $isValid = $this->isValidId($id);
        if ($allowMe===true) {
            if ($id==="me") {
                $isValid = true;
            }
        }



        if ($isValid!==true) {


            $e = new Lib_Application_Exception("Invalid graph id");
            $e->setMethod($errorMethod);
            $e->setFault(array(
                             "id" => $id,
                             "allowMe" => $allowMe,
                         ));
            throw $e;
        }

    }


	
}
