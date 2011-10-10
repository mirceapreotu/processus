<?php
/**
 * App_Facebook_Dispatcher_Canvas
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Facebook_Dispatcher
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

/**
 * App_Facebook_Dispatcher_Canvas
 *
 *
 *
 * @category	meetidaaa.com
 * @package        App_Facebook_Dispatcher
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id:$
 */

class App_Facebook_Dispatcher_Canvas
{


    // ++++++++ request keys to detect ++++++++++
    const REQUEST_KEY_ACTION = "meetidaaa_action";

    //const REQUEST_KEY_REF = "ref";
    //const REQUEST_KEY_FOO = "foo";
    //const REQUEST_KEY_QUESTION = "question";


    // ++++ actions +++++++++++

    const ACTION_FANPAGE_AUTH = "fanpageauth";


    //const ACTION_COMMENTS = "comments";





    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }

    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



    public function run()
    {



        $action = $this->getActionDecodedFromRequest();

        /*
        $actionFromRef = $this->getActionCommandFromRequestKey(self::REQUEST_KEY_REF);
        switch ($actionFromRef) {

            case self::ACTION_COMMENTS: {
                $this->actionComments();
                return;
                break;
            }

            default: {
                break;
            }
        }
        */

        /*
        $actionFromQuestion = $this->getActionCommandFromRequestKey(self::REQUEST_KEY_QUESTION);
        $actionFromQuestion=(string)$actionFromQuestion;
        if (Lib_Utils_String::isEmpty($actionFromQuestion)!==true) {
            $this->actionComments();
            return;

        }
        */

        /*
        $actionFromFoo = $this->getActionCommandFromRequestKey(self::REQUEST_KEY_FOO);
        switch ($actionFromFoo) {

            case self::ACTION_COMMENTS: {
                $this->actionComments();
                return;
                break;
            }

            default: {
                break;
            }
        }
        */

        switch ($action["method"]) {
            case self::ACTION_FANPAGE_AUTH:
                {
                $this->actionFanpageAuth();
                    return;
                break;
                }
                /*
                case self::ACTION_COMMENTS:
                {
                $this->actionComments();
                    return;
                break;
                }
                */
            default:
                {

                $this->actionDefault();
                break;
                }
        }


    }


    /**
        * @return
        */
       public function actionDefault()
       {
           // do the ususal canvas stuff
           include Bootstrap::getRegistry()->getHtdocsPath("fb/canvas/canvas.php");
           //include dirname(__FILE__)."/canvas.php";
           return;
       }


       /**
        * @return void
        */
       public function actionFanpageAuth()
       {

           $application = $this->getApplication();
           $facebook = $this->getFacebook();

           $action = $this->getActionDecodedFromRequest();
           $actionMethod = Lib_Utils_Array::getProperty($action, "method");

           $actionPageId = Lib_Utils_Array::getProperty($action, "pageId");


           // and delegate deeplink's: app_data if there are any

           $appData =  Lib_Utils_Array::getProperty($_GET, "app_data");

           if (Lib_Utils_String::isEmpty($appData)) {
               $appData = "";
           }

           
           $appData = json_decode($appData, true);

           $appDataMixin = array(
                    "appInstalled" =>true,
           );

           if (is_array($appData)!==true) {
               $appData = array(

               );
           }

           foreach($appDataMixin as $key => $value) {
               $appData[$key] = $value;
           }


           $redirectUrl = $application->newUrlToFanpage($actionPageId, $appData);
           header('Location: ' . $redirectUrl);



       }


    
     /**
        * @return void
        */
    /*
       public function actionComments()
       {
            // do the ususal canvas stuff
           include Bootstrap::getRegistry()->getHtdocsPath("fb/canvas/comments.php");
           //include dirname(__FILE__)."/canvas.php";
           return;



       }

    */


    
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @return array
     */
    /*
    public function newActionComments()
    {


        $action = array(
            "method" => self::ACTION_COMMENTS,
        );

        return $action;
    }

    */


    /**
     * @throws Exception
     * @param  int|string|null $fanpageId
     * @return array
     */
    public function newActionFanpageAuth($fanpageId)
    {

        if (($fanpageId==="")) {
            $fanpageId = null;
        }

        if ($fanpageId!==null) {

            if ($this->isValidId($fanpageId)!==true) {
                throw new Exception(
                    "Invalid parameter 'fanpageId' at ".__METHOD__);
            }

        }

        $action = array(
            "method" => self::ACTION_FANPAGE_AUTH,
            "pageId" => $fanpageId,
        );

        return $action;
    }



       /**
     * @return array
     */
    public function getActionDecodedFromRequest()
    {
        $actionJson = (string)Lib_Utils_Array::getProperty(
            $_GET, self::REQUEST_KEY_ACTION
        );

        $actionDecoded = json_decode($actionJson, true);
        if (is_array($actionDecoded)!==true) {
            $actionDecoded = array(
                "method" => null,
            );
        }

        return $actionDecoded;
    }


      /**
     * @return array
     */
    public function getActionCommandFromRequestKey($key)
    {
        $result = null;
        if (Lib_Utils_String::isEmpty($key)) {
            return $result;
        }

        $action = (string)Lib_Utils_Array::getProperty(
            $_GET, $key
        );

        if (Lib_Utils_String::isEmpty($action)) {
            return $result;
        }

        return $action;
    }

    
    /**
     * @param  int|string $id
     * @return bool
     */
    public function isValidId($id)
    {
        $id = Lib_Utils_TypeCast_String::asUnsignedBigIntString(
            $id, null
        );

        if ($id!==null) {
            return true;
        }

        return false;
    }




    




    /**
     * @param  int|string|null $fanpageId
     * @param  null|string|mixed $appData
     * @return string
     */
    public function newFanpageLoginUrlRedirectUri($fanpageId, $appData)
    {


        $url = $this->getApplication()->getUrl("fb/canvas/index.php");
        $zendUri = new Lib_Url_Uri($url);

        $appDataJson = null;
        if ($appData === "") {
            $appData = null;
        }
        if ($appData !== null) {
            if (is_string($appData)) {
                $appDataJson = $appData;
            } else {
                $appDataJson = json_encode($appData);
            }
        }

        if (Lib_Utils_String::isEmpty($appDataJson)!==true) {
            $zendUri->setQueryParameter("app_data", $appDataJson);
        }


        $action = $this->newActionFanpageAuth($fanpageId);
        $actionJson = json_encode($action);
        $zendUri->setQueryParameter(self::REQUEST_KEY_ACTION, $actionJson);




        $url = $zendUri->toString(null);
        return $url;

    }






}
